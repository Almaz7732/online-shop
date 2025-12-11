<footer class="footer">
    <!-- Start Footer Top -->
































    <!-- End Footer Top -->

    <!-- Start Footer Middle -->
    <div class="footer-middle">
        <div class="container">
            <div class="bottom-inner">
                <div class="row justify-content-center">
                    <div class="col-lg-3 col-md-6 col-12">
                        <!-- Single Widget -->
                        <div class="single-footer f-contact">
                            <h3>Свяжитесь с нами</h3>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(setting('site_phone')): ?>
                                <p class="phone">Телефон: <?php echo e(setting('site_phone')); ?></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <ul>
                                <li><span>Понедельник-Пятница: </span> 9:00 - 18:00 вечера</li>
                                <li><span>Суббота: </span> 9:00 - 18:00 вечера</li>
                            </ul>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(setting('site_email')): ?>
                                <p class="mail">
                                    <a href="mailto:<?php echo e(setting('site_email')); ?>"><?php echo e(setting('site_email')); ?></a>
                                </p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(setting('site_address')): ?>
                                <p class="address">
                                    <i class="lni lni-map-marker"></i> <?php echo e(setting('site_address')); ?>

                                </p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <!-- End Single Widget -->
                    </div>























                    <div class="col-lg-3 col-md-6 col-12">
                        <!-- Single Widget -->
                        <div class="single-footer f-link">
                            <h3>Информация</h3>
                            <ul>
                                <li><a href="<?php echo e(route('shop.products')); ?>">Товары</a></li>
                                <li><a href="<?php echo e(route('shop.about')); ?>">О нас</a></li>
                            </ul>
                        </div>
                        <!-- End Single Widget -->
                    </div>














                </div>
            </div>
        </div>
    </div>
    <!-- End Footer Middle -->

    <!-- Start Footer Bottom -->
    <div class="footer-bottom">
        <div class="container">
            <div class="inner-content">
                <div class="row align-items-center">






                    <div class="col-lg-4 col-12">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(setting('site_facebook') || setting('site_twitter') || setting('site_instagram') || setting('site_youtube')): ?>
                            <ul class="socila">
                                <li>
                                    <span>Подписывайтесь на нас:</span>
                                </li>
                                <?php if(setting('site_facebook')): ?>
                                    <li><a href="<?php echo e(setting('site_facebook')); ?>" target="_blank"><i class="lni lni-facebook-filled"></i></a></li>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(setting('site_twitter')): ?>
                                    <li><a href="<?php echo e(setting('site_twitter')); ?>" target="_blank"><i class="lni lni-twitter-original"></i></a></li>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(setting('site_instagram')): ?>
                                    <li><a href="<?php echo e(setting('site_instagram')); ?>" target="_blank"><i class="lni lni-instagram"></i></a></li>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(setting('site_youtube')): ?>
                                    <li><a href="<?php echo e(setting('site_youtube')); ?>" target="_blank"><i class="lni lni-youtube"></i></a></li>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </ul>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Footer Bottom -->
</footer>
<?php /**PATH /var/www/html/resources/views/clients/partials/footer.blade.php ENDPATH**/ ?>