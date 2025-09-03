<footer class="peugeot-footer">
    <div class="peugeot-footer-top">
        <div class="container-fluid">
            <div class="row g-0 text-center">
                <div class="col-lg-3 col-6 peugeot-footer-top-item">
                    <span class="footer-icon"><i class="fa fa-map-marker"></i></span>
                    <span class="footer-label">TÌM ĐẠI LÝ GẦN NHẤT</span>
                </div>
                <div class="col-lg-3 col-6 peugeot-footer-top-item">
                    <span class="footer-icon"><i class="fa fa-car"></i></span>
                    <span class="footer-label">ĐẶT LỊCH LÁI THỬ</span>
                </div>
                <div class="col-lg-3 col-6 peugeot-footer-top-item">
                    <span class="footer-icon"><i class="fa fa-question"></i></span>
                    <span class="footer-label">TRỢ GIÚP</span>
                </div>
                <div class="col-lg-3 col-6 peugeot-footer-top-item">
                    <span class="footer-icon"><i class="fa fa-envelope"></i></span>
                    <span class="footer-label">LIÊN HỆ</span>
                </div>
            </div>
        </div>
    </div>
    <div class="peugeot-footer-main">
        <div class="container-fluid">
            <div class="row peugeot-footer-main-row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="footer-title">VỀ PEUGEOT VIỆT NAM</h5>
                    <p class="footer-desc">
                        CÔNG TY TNHH PHÂN PHỐI THACO AUTO Giấy CNĐKDN: 400077880<br>
                        Ngày cấp 27/10/2010
                    </p>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="footer-title">TRUY CẬP NHANH</h5>
                    <ul class="footer-links">
                        <li><a href="#">Yêu cầu báo giá</a></li>
                        <li><a href="#">Đăng ký lái thử</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="footer-title">DÀNH CHO CHỦ XE PEUGEOT</h5>
                    <ul class="footer-links">
                        <li><a href="#">Bảo dưỡng</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="footer-title">TÌM HIỂU THÊM VỀ PEUGEOT</h5>
                    <ul class="footer-links">
                        <li><a href="#">PEUGEOT Motocycles</a></li>
                        <li><a href="#">Thông báo Pháp lý</a></li>
                        <li><a href="#">Chính sách Bảo mật Thông tin Cá nhân</a></li>
                    </ul>
                    <div class="footer-hotline mt-3 fw-bold">HOTLINE: 1900 1101</div>
                    <div class="footer-social mt-3">
                        <a href="#" class="footer-social-icon"><i class="fa fa-facebook"></i></a>
                        <a href="#" class="footer-social-icon"><i class="fa fa-instagram"></i></a>
                        <a href="#" class="footer-social-icon"><i class="fa fa-youtube"></i></a>
                        <a href="#" class="footer-social-icon"><i class="fa fa-linkedin"></i></a>
                    </div>
                </div>
            </div>
            <div class="row peugeot-footer-bottom-row text-center">
                <div class="col-12 mt-4">
                    <div class="footer-logo">
                        <?php
                        if (function_exists('the_custom_logo') && has_custom_logo()) {
                            the_custom_logo();
                        }
                        ?>
                    </div>
                </div>
                <div class="col-12 mt-2">
                    <div class="footer-policy-links">
                        <a href="#">Chính sách & Quy định chung</a> |
                        <a href="#">Chính sách Bảo mật Thông tin</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="peugeot-footer-bottom text-center py-3">
        &copy; <?php echo date('Y'); ?> | Thiết kế & phát triển bởi <span class="fw-bold text-primary">MinhTris</span>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>