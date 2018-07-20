<style xmlns="http://www.w3.org/1999/html">




    .mega-menu-box .display-posts-listing a.title {
        font-size: inherit;
    }




</style>
<?php
$site_home = home_url();
?>

<div id="jo-mega-menu-trigger" style="background-color: #fff;"><p><span id="jo-mega-menu-trigger-text">Show Mega Menu</span><br/><span><img id="jo-mega-menu-trigger-img" style="max-width: 20%;" src="<?php //echo plugin_dir_url(); ?>caldol-mega-menu/public/img/down_double_arrow_color.png"/></span></p></div>
<div id="jo-mega-menu">

<div class="mega-menu-wrapper">
    <div class="mega-menu-box communities">
        <div>
            <div class="mega-menu-title">COMMUNITIES</div>
            <ul>
                <?php //echo do_shortcode("[caldol-whats-new-form]"); ?>
                <li><a href="<?php echo $site_home; ?>/arng-usar-toc">ARNG/USAR</a></li>
                <li><a href="<?php echo $site_home; ?>/bolc-toc">BOLC</a></li>
                <li><a href="<?php echo $site_home; ?>/cc-toc">Company Command</a></li>
                <li><a href="<?php echo $site_home; ?>/staff-toc">Staff</a></li>
                <li><a href="<?php echo $site_home; ?>/pl-toc">Platoon Leader</a></li>
            </ul>

        </div>
    </div>
        <div class="mega-menu-box topics">
            <div class="mega-menu-title">TOPIC AREAS</div>
            <ul>
                <?php //echo do_shortcode("[caldol-whats-new-form]"); ?>
                <li><a href="<?php echo $site_home; ?>/additional-duties-topic">Additional Duties</a></li>
                <li><a href="<?php echo $site_home; ?>/fitness-topic">Fitness</a></li>
                <li><a href="<?php echo $site_home; ?>/leadership-topic">Leadership</a></li>
                <li><a href="<?php echo $site_home; ?>/maintenance-topic">Maintenance</a></li>
                <li><a href="<?php echo $site_home; ?>/operations-topic">Operations</a></li>
                <li><a href="<?php echo $site_home; ?>/personnel-topic">Personnel</a></li>
                <li><a href="<?php echo $site_home; ?>/planning-topic">Planning</a></li>
                <li><a href="<?php echo $site_home; ?>/self-development-topic">Self Development</a></li>
                <li><a href="<?php echo $site_home; ?>/soldiers-and-families-topic">Soldiers and Families</a></li>
                <li><a href="<?php echo $site_home; ?>/supply-topic">Supply</a></li>
                <li><a href="<?php echo $site_home; ?>/training-topic">Training</a></li>
            </ul>
        </div>

 <div class="mega-menu-box special">
        <div>
            <div class="mega-menu-title">SPECIAL FORUMS</div>
            <ul>
                <li><a href="<?php echo $site_home; ?>/forum/read2lead">Read2Lead</a></li>
                <li><a href="<?php echo $site_home; ?>/forum/leader-log">Leader Log</a></li>

            </ul>


        </div>
    </div>

    <div class="mega-menu-box resources">
        <div>
            <div class="mega-menu-title">RESOURCES</div>
            <ul>
                <li><a href="<?php echo $site_home; ?>/army-magazine">Army Magazine Articles</a></li>
                <li><a href="<?php echo $site_home; ?>/cclpd-public">CCLPDs</a></li>
                <li><a href="<?php echo $site_home; ?>/jo-newsletters">JO Newsletters</a></li>
                <li><a href="<?php echo $site_home; ?>/jo-polls">JO Polls</a></li>
                <li><a href="<?php echo $site_home; ?>/leaders-huddle">Leaders Huddle Podcast</a></li>
                <li><a href="<?php echo $site_home; ?>/outside-the-wire">Outside the Wire</a></li>
                <li><a href="<?php echo $site_home; ?>/tag/tuesday-tools/">Tuesday Tools</a></li>
               <li><a href="<?php echo $site_home; ?>/tools/">Tools (all)</a></li>


                <li style="list-style: none;">------------</li>
                <li><a href="<?php echo $site_home; ?>/about-jo">About JO</a></li>
                  <li><a href="<?php echo $site_home; ?>/discussion/bughunter">Bug Hunter -- Report a Bug!</a></li>
                  <li><a href="<?php echo $site_home; ?>/contact-us">Contact Us</a></li>
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
                    <li><a href="<?php echo $site_home; ?>/member-search/">Custom Member Search</a></li>

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
            <li><a href="#">Menu 1</a>
                <div>
                    <ul>
                        <li><a href="#">This is a long sub item 1</a></li>
                        <li><a href="#">sub item 2</a></li>
                        <li><a href="#">sub item 3</a></li>
                    </ul>

                    <ul>
                        <li><a href="#">Part two: sub item 1</a></li>
                        <li><a href="#">Part two: sub item 2</a></li>
                        <li><a href="#">Part two: sub item 3</a></li>
                        <li><a href="#">Part two: sub item 4</a></li>
                    </ul>
                </div>
            </li>
            <li><a href="#">Menu 2</a>
                <div>
                    <ul>
                        <li><a href="#">different sub item 1</a></li>
                        <li><a href="#">different sub item 2</a></li>
                    </ul>
                </div>
            </li>
            <li><a href="#">Menu 3</a>
                <div>
                    <ul>
                        <li><a href="#">some more items 1</a></li>
                        <li><a href="#">here's a really long item 2</a></li>
                        <li><a href="#">some more items 3</a></li>
                        <li><a href="#">short item 4</a></li>
                        <li><a href="#">some more items 5</a></li>
                        <li><a href="#">item 6</a></li>
                    </ul>
                </div>
            </li>
        </ul>
    </nav>
</div>
-->
