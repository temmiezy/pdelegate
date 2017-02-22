<!-- Header banner Start Here -->
            <div class="header-banner">
                <div class="container">
                    <div class="header-title">
                        <h2>My Account - <?php echo $username; ?></h2>
                    </div>
                    <div class="breadcrumb">
                        <ul>
                            <li><a href="index.html">Home -</a></li>
                            <li class="active">My Account</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Header banner End Here -->
            <!-- Shop page Start Here -->
            <div class="product-category-area padding-top-bottom">
                <div class="container">
                    <div class="row">
                        <!-- Sidebar Start Here -->
                        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                            <div class="right-sidebar">
                                <div class="single-sidebar">
                                    <h3>Menu</h3>
                                    <ul class="category-menu">
                                        <li><a href="<?= base_url() ?>accounts/requests">Home</a></li>
                                        <li><a href="<?= base_url() ?>accounts/index">Requests</a></li>
                                        <li><a href="#">Profile</a></li>
                                        <li><a href="<?= base_url() ?>accounts/signout">Sign out</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <h2>Rates</h2>
                                    <p>Our Rate: $<?php echo $rate['rate']; ?></p>
                                    <p>Western Union: $375</p>
                                </div>
                            </div>
                        </div>
                        <!-- Sidebar End Here -->