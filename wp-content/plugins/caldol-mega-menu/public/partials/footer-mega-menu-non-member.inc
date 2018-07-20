<style xmlns="http://www.w3.org/1999/html">




    .mega-menu-box .display-posts-listing a.title {
        font-size: inherit;
    }




</style>
<?php
$site_home = home_url();
?>

<div id="jo-mega-menu-trigger" class="not-logged-in">
    <div>
        <h1 style="text-align: center;">Not yet a member of the Junior Officer (JO) Forum?</h1>
        <h2>The gray, inactive links in the menu below will give you a good idea as to what is available to JO Members.  Unless you are a JO member, you are not able to access that content, however, the Junior Officer Forum does provide some useful resources to non-members as well.  Resources that are available to non-JO members are at the gold, active links below.</h2>
    </div><p>Join JO Today!<br/><a href="<?php echo site_url();?>/register" class="shortcode button    large gray home-register-button">Register</a></p></div>
<div id="jo-mega-menu" class="not-logged-in">

    <div class="mega-menu-wrapper">
        <div class="mega-menu-box communities">
            <div>
                <div class="mega-menu-title">COMMUNITIES</div>
                <ul>
                    <li><a>ARNG/USAR</a></li>
                    <li><a>BOLC</a></li>
                    <li><a>Company Command</a></li>
                    <li><a>Staff</a></li>
                    <li><a>Platoon Leader</a></li>
                </ul>

            </div>
        </div>
        <div class="mega-menu-box topics">
            <div class="mega-menu-title">TOPIC AREAS</div>
            <ul>
                <?php //echo do_shortcode("[caldol-whats-new-form]"); ?>
                <li><a>Additional Duties</a></li>
                <li><a>Fitness</a></li>
                <li><a>Leadership</a></li>
                <li><a>Maintenance</a></li>
                <li><a>Operations</a></li>
                <li><a>Personnel</a></li>
                <li><a>Planning</a></li>
                <li><a>Self Development</a></li>
                <li><a>Soldiers and Families</a></li>
                <li><a>Supply</a></li>
                <li><a>Training</a></li>
            </ul>
        </div>

        <div class="mega-menu-box special">
            <div>
                <div class="mega-menu-title">SPECIAL FORUMS</div>
                <ul>
                    <li><a>Read2Lead</a></li>
                    <li><a>Leader Log</a></li>

                </ul>


            </div>
        </div>

        <div class="mega-menu-box resources">
            <div>
                <div class="mega-menu-title">RESOURCES</div>
                <ul>
                    <li><a class="active" href="<?php echo $site_home; ?>/army-magazine">Army Magazine Articles</a></li>
                    <li><a class="active" href="<?php echo $site_home; ?>/cclpd-public">CCLPDs</a></li>
                    <li><a>JO Newsletters</a></li>
                    <li><a>JO Polls</a></li>
                    <li><a class="active" href="<?php echo $site_home; ?>/leaders-huddle">Leaders Huddle Podcast</a></li>
                    <li><a>Outside the Wire</a></li>
                    <li><a>Tuesday Tools</a></li>
                    <li><a>Tools (all)</a></li>


                    <li style="list-style: none;">------------</li>
                    <li><a class="active" href="<?php echo $site_home; ?>/about-jo">About JO</a></li>
                    <li><a>Bug Hunter -- Report a Bug!</a></li>
                    <li><a class="active" href="<?php echo $site_home; ?>/contact-us">Contact Us</a></li>
                </ul>


            </div>
        </div>




        <div class="mega-menu-box discussions">
            <div>
                <div class="mega-menu-title">LATEST DISCUSSIONS</div>

                <p>
                    <?php echo do_shortcode("[display-posts post_type=\"topic\" orderby=\"date\" posts_per_page=\"5\"]"); ?>
                </p>



            </div>

        </div>
        <div class="mega-menu-box content">

            <div>
                <div class="mega-menu-title">LATEST CONTENT</div>

                <p>
                    <?php echo do_shortcode("[display-posts category=\"cat-type\" order=\"DESC\"  posts_per_page=\"5\"]"); ?>
                </p>

            </div>

        </div>

        <div class="mega-menu-box comments">

            <div>
                <div class="mega-menu-title">MOST COMMENTS</div>

                <p>
                    <?php echo do_shortcode("[display-posts orderby=\"comment_count\" order=\"DESC\" posts_per_page=\"5\"]"); ?>
                </p>

            </div>

        </div>

        <div class="mega-menu-box views">

            <div>
                <div class="mega-menu-title">MOST VIEWS</div>

                <p>
                    <?php echo do_shortcode("[display-posts orderby=\"meta_value_num\" meta_key=\"_post_views_count\" order=\"DESC\" posts_per_page=\"5\"]"); ?>
                </p>

            </div>

        </div>




    </div>
    <div class="mega-menu-wrapper lower">
        <div class="mega-menu-box discussion-tags">
            <div>
                <p style="text-align:center;"><div class="mega-menu-title">POPULAR DISCUSSION TAGS</div></p>

                <?php


                $args = array(
                    'smallest'                  => 8,
                    'largest'                   => 22,
                    'unit'                      => 'pt',
                    'number'                    => 45,
                    'format'                    => 'flat',
                    'separator'                 => "\n",
                    'orderby'                   => 'name',
                    'order'                     => 'ASC',
                    'exclude'                   => null,
                    'include'                   => null,

                    'link'                      => 'view',
                    'taxonomy'                  => 'topic-tag',
                    'echo'                      => true,
                    'child_of'                  => null, // see Note!
                );
                wp_tag_cloud( $args); ?>



            </div>
        </div>
        <div class="mega-menu-box search">
            <div>
                <div class="mega-menu-title">SEARCH</div>
                <ul>
                    <li><p><?php get_search_form( $echo = true );?></p</li>
                    <li><a>Custom Member Search</a></li>

                </ul>


            </div>
        </div>
        <div class="mega-menu-box content-tags">
            <p style="text-align:center;"> <div class="mega-menu-title">POPULAR CONTENT TAGS</div></p>
            <?php


            $args = array(
                'smallest'                  => 8,
                'largest'                   => 22,
                'unit'                      => 'pt',
                'number'                    => 45,
                'format'                    => 'flat',
                'separator'                 => "\n",
                'orderby'                   => 'name',
                'order'                     => 'ASC',
                'exclude'                   => null,
                'include'                   => null,

                'link'                      => 'view',
                'taxonomy'                  => 'post_tag',
                'echo'                      => true,
                'child_of'                  => null, // see Note!
            );
            wp_tag_cloud( $args); ?>
        </div>




    </div>
</div>


<!--Googe
<div id="footer-mega-menu-wrapper">

    <nav>
        <ul>
            <li><a class="active" href="#">Menu 1</a>
                <div>
                    <ul>
                        <li><a class="active" href="#">This is a long sub item 1</a></li>
                        <li><a class="active" href="#">sub item 2</a></li>
                        <li><a class="active" href="#">sub item 3</a></li>
                    </ul>

                    <ul>
                        <li><a class="active" href="#">Part two: sub item 1</a></li>
                        <li><a class="active" href="#">Part two: sub item 2</a></li>
                        <li><a class="active" href="#">Part two: sub item 3</a></li>
                        <li><a class="active" href="#">Part two: sub item 4</a></li>
                    </ul>
                </div>
            </li>
            <li><a class="active" href="#">Menu 2</a>
                <div>
                    <ul>
                        <li><a class="active" href="#">different sub item 1</a></li>
                        <li><a class="active" href="#">different sub item 2</a></li>
                    </ul>
                </div>
            </li>
            <li><a class="active" href="#">Menu 3</a>
                <div>
                    <ul>
                        <li><a class="active" href="#">some more items 1</a></li>
                        <li><a class="active" href="#">here's a really long item 2</a></li>
                        <li><a class="active" href="#">some more items 3</a></li>
                        <li><a class="active" href="#">short item 4</a></li>
                        <li><a class="active" href="#">some more items 5</a></li>
                        <li><a class="active" href="#">item 6</a></li>
                    </ul>
                </div>
            </li>
        </ul>
    </nav>
</div>
-->
