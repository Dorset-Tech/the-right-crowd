<?php 
/*
 * Template Name: Portfolio Page
 */
get_header(); ?>
<div class="portfolio-main">
	<div class="featuredimag" style="background:url('https://thesharehub.co.uk/wp-content/uploads/about.jpg') no-repeat;">
        <div class="container">
            <h2>Portfolio</h2>
        </div>
    </div>
    <div class="subheader">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h4>Portfolio</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="port-filter">
        <div class="container">
            <div class="port-filter-main">
                <div class="filtername">
                    <input type="text" placeholder="Filter by name">
                    <i class="fa fa-search"></i>
                </div>
                <div class="filterselect">
                    <label>Shareholder</label>
                    <select>
                        <option>All</option>
                        <option>Test</option>
                        <option>Test</option>
                    </select>
                </div>
                <div class="filterselect">
                    <label>Showing</label>
                    <select>
                        <option>All</option>
                        <option>Test</option>
                        <option>Test</option>
                    </select>
                </div>
                <div class="filterselect">
                    <label>Order by</label>
                    <select>
                        <option>Newest First</option>
                        <option>Old</option>
                    </select>
                </div>
                <div class="filterselect">
                    <input type="submit" value="Search">
                </div>
            </div>
            <div class="profoliosec">
                <ul>
                    <li>
                        <a href="">
                            <img src="<?php echo get_template_directory_uri(); ?>/images/fb.jpg">
                        </a>
                    </li>
                    <li>
                        <a href="">
                            <img src="<?php echo get_template_directory_uri(); ?>/images/SLACK.jpg">
                        </a>
                    </li>
                    <li>
                        <a href="">
                            <img src="<?php echo get_template_directory_uri(); ?>/images/sup.jpg">
                        </a>
                    </li>
                    <li>
                        <a href="">
                            <img src="<?php echo get_template_directory_uri(); ?>/images/qualt.jpg">
                        </a>
                    </li>
                    <li>
                        <a href="">
                            <img src="<?php echo get_template_directory_uri(); ?>/images/bla.jpg">
                        </a>
                    </li>
                    <li>
                        <a href="">
                            <img src="<?php echo get_template_directory_uri(); ?>/images/avito.jpg">
                        </a>
                    </li>
                    <li>
                        <a href="">
                            <img src="<?php echo get_template_directory_uri(); ?>/images/delivero.jpg">
                        </a>
                    </li>
                    <li>
                        <a href="">
                            <img src="<?php echo get_template_directory_uri(); ?>/images/spotify.jpg">
                        </a>
                    </li>
                    <li>
                        <a href="">
                            <img src="<?php echo get_template_directory_uri(); ?>/images/dropbox.jpg">
                        </a>
                    </li>
                    <li>
                        <a href="">
                            <img src="<?php echo get_template_directory_uri(); ?>/images/flipcart.jpg">
                        </a>
                    </li>
                    <li>
                        <a href="">
                            <img src="<?php echo get_template_directory_uri(); ?>/images/alt.jpg">
                        </a>
                    </li>
                    <li>
                        <a href="">
                            <img src="<?php echo get_template_directory_uri(); ?>/images/fundin.jpg">
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>