</div>

<footer class="footer">
  <?php for ($i = 0; $i < 37; $i++): ?>
    <div class="footer__hex">
      <?php if ($i == 18): ?>
        <span class="footer__text footer__text--large">&copy;</span>
        <span class="footer__text">Dan Hart</span>
        <span class="footer__text"><?php echo date('Y'); ?></span>
      <?php endif; ?>
    </div>
  <?php endfor; ?>
</footer>

<?php wp_footer(); ?>
</body>
</html>