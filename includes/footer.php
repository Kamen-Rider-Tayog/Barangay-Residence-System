<footer class="footer">
    <div class="footer-content">
        <div>
            <h5><?php echo __('f-contact'); ?></h5>
            <p><i class="fas fa-phone"></i> (+63) 966 988 0213</p>
            <p><i class="fas fa-envelope"></i> brgy.sanfrancisco.gentri@gmail.com</p>
            <p><i class="fas fa-map-marker-alt"></i> San Francisco, Gen. Trias, Cavite</p>
        </div>
        <div>
            <h5><?php echo __('f-links'); ?></h5>
            <ul>
                <li><a href="/barangay-residence-system/pages/index.php"><?php echo __('nav-home'); ?></a></li>
                <li><a href="/barangay-residence-system/pages/services.php"><?php echo __('nav-services'); ?></a></li>
                <li><a href="/barangay-residence-system/pages/login.php"><?php echo __('login'); ?></a></li>
                <li><a href="?reset_announcement=1" style="font-size: 0.625rem; color: gray;">Reset Announcement</a></li>
            </ul>
        </div>
        <div>
            <h5><?php echo __('f-follow'); ?></h5>
            <div class="social-icons">
                <a href="https://www.facebook.com/tayog.kagame" target="_blank"><i class="fab fa-facebook"></i> Facebook</a>
                <a href="https://www.instagram.com/_tayog/" target="_blank"><i class="fab fa-instagram"></i> Instagram</a>
                <a href="#"><i class="fab fa-twitter" target="_blank"></i> Twitter</a>
            </div>
        </div>
    </div>
    <p class="footer-copyright"><?php echo __('footer-copy'); ?></p>
    <p class="footer-copyright">Made with <i class="fa-regular fa-heart"></i> by 
        <a href="https://kamen-rider-tayog.github.io/tayog-portfolio/" target="blank">Kagame</a>
    </p>
</footer>

<script src="/barangay-residence-system/assets/js/main.js"></script>

<?php if (isset($page_js)): ?>
<script src="/barangay-residence-system/assets/js/pages/<?php echo $page_js; ?>"></script>
<?php endif; ?>

</body>
</html>