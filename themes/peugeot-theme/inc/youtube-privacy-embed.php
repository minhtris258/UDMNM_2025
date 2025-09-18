<?php
/**
 * YouTube privacy embed helper
 * - Chuyển mọi URL YouTube về dạng youtube-nocookie.com
 * - Ưu tiên dùng oEmbed của WP, fallback nếu oEmbed fail
 * - Hạn chế cookie bên thứ ba khi tải trang
 */

if (!function_exists('pg_youtube_privacy_iframe')) {
  /**
   * @param string $url  Link YouTube (watch?v=, youtu.be, shorts/...)
   * @param array  $args width, height, autoplay, lazy
   * @return string HTML <iframe> hoặc '' nếu không hợp lệ
   */
  function pg_youtube_privacy_iframe($url, array $args = []) {
    if (empty($url)) return '';

    $width    = isset($args['width'])  ? (int)$args['width']  : 1200;
    $height   = isset($args['height']) ? (int)$args['height'] : 700;
    $autoplay = !empty($args['autoplay']) ? 1 : 0;
    $lazy     = array_key_exists('lazy', $args) ? (bool)$args['lazy'] : true;

    // 1) Thử oEmbed trước
    $html = wp_oembed_get($url, ['width' => $width, 'height' => $height]);

    // Hàm đổi iframe sang youtube-nocookie + tinh chỉnh thuộc tính
    $convert_to_nocookie = function($iframeHtml) use ($autoplay, $lazy, $width, $height) {
      if (empty($iframeHtml) || stripos($iframeHtml, '<iframe') === false) return '';

      if (preg_match('~src=["\']([^"\']+)["\']~i', $iframeHtml, $m)) {
        $src = html_entity_decode($m[1]);

        // Chỉ xử lý khi là YouTube
        if (stripos($src, 'youtube.com/') === false && stripos($src, 'youtu.be/') === false) {
          return $iframeHtml;
        }

        // Parse URL
        $parts  = wp_parse_url($src);
        $scheme = $parts['scheme'] ?? 'https';
        $path   = $parts['path']   ?? '/';
        $query  = [];
        if (!empty($parts['query'])) parse_str($parts['query'], $query);

        // Lấy VIDEO_ID
        $videoId = '';
        if (preg_match('~/embed/([A-Za-z0-9_-]{6,})~', $path, $mm)) {
          $videoId = $mm[1];
        } elseif (!empty($query['v'])) {
          $videoId = $query['v'];
        } elseif (preg_match('~youtu\.be/([A-Za-z0-9_-]{6,})~', $src, $mm)) {
          $videoId = $mm[1];
        } elseif (preg_match('~/shorts/([A-Za-z0-9_-]{6,})~', $src, $mm)) {
          $videoId = $mm[1];
        }
        if (empty($videoId)) return $iframeHtml;

        // Làm sạch tham số
        unset($query['si'], $query['feature']);

        // Thêm tham số UX
        $query = array_merge([
          'rel'            => '0',
          'modestbranding' => '1',
          'playsinline'    => '1',
        ], $query);
        if ($autoplay) $query['autoplay'] = '1';

        // Build src mới
        $newSrc = $scheme . '://www.youtube-nocookie.com/embed/' . rawurlencode($videoId);
        if (!empty($query)) $newSrc .= '?' . http_build_query($query);

        // Thay src
        $iframe = preg_replace('~src=["\'][^"\']+["\']~i', 'src="' . esc_url($newSrc) . '"', $iframeHtml);

        // Fix width/height
        $iframe = preg_replace('~\swidth=["\']\d+["\']~i', ' width="' . $width . '"', $iframe);
        $iframe = preg_replace('~\sheight=["\']\d+["\']~i', ' height="' . $height . '"', $iframe);

        // Thuộc tính an toàn
        if ($lazy && stripos($iframe, ' loading=') === false) {
          $iframe = str_replace('<iframe', '<iframe loading="lazy"', $iframe);
        }
        if (stripos($iframe, ' allowfullscreen') === false) {
          $iframe = str_replace('<iframe', '<iframe allowfullscreen', $iframe);
        }
        if (stripos($iframe, ' referrerpolicy=') === false) {
          $iframe = str_replace('<iframe', '<iframe referrerpolicy="strict-origin-when-cross-origin"', $iframe);
        }
        if (stripos($iframe, ' allow=') === false) {
          $iframe = str_replace(
            '<iframe',
            '<iframe allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"',
            $iframe
          );
        }

        return $iframe;
      }

      return $iframeHtml;
    };

    if (!empty($html)) {
      return $convert_to_nocookie($html);
    }

    // 2) Fallback: tự trích VIDEO_ID và build iframe nocookie
    $videoId = '';
    if (preg_match('~(?:youtu\.be/|v=|shorts/)([A-Za-z0-9_-]{6,})~', $url, $m)) {
      $videoId = $m[1];
    }
    if (empty($videoId)) return '';

    $params = [
      'rel'            => '0',
      'modestbranding' => '1',
      'playsinline'    => '1',
    ];
    if ($autoplay) $params['autoplay'] = '1';

    $src = 'https://www.youtube-nocookie.com/embed/' . rawurlencode($videoId) . '?' . http_build_query($params);

    return sprintf(
      '<iframe src="%s" width="%d" height="%d" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen%s></iframe>',
      esc_url($src),
      $width,
      $height,
      $lazy ? ' loading="lazy"' : ''
    );
  }
}

/* (Tuỳ chọn) Shortcode: [yt_privacy url="..."]
   Ví dụ: [yt_privacy url="https://youtu.be/TSQsMyk8zSc" width="1200" height="700" lazy="1" autoplay="0"] */
if (!shortcode_exists('yt_privacy')) {
  function pg_sc_youtube_privacy($atts) {
    $a = shortcode_atts([
      'url'      => '',
      'width'    => 1200,
      'height'   => 700,
      'lazy'     => 1,
      'autoplay' => 0,
    ], $atts, 'yt_privacy');

    return pg_youtube_privacy_iframe($a['url'], [
      'width'    => (int)$a['width'],
      'height'   => (int)$a['height'],
      'lazy'     => (bool)$a['lazy'],
      'autoplay' => (bool)$a['autoplay'],
    ]);
  }
  add_shortcode('yt_privacy', 'pg_sc_youtube_privacy');
}
