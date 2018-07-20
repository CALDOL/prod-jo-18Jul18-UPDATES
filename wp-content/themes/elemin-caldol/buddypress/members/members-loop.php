<?php
/**
 * BuddyPress - Members Loop
 *
 * Querystring is set via AJAX in _inc/ajax.php - bp_legacy_theme_object_filter()
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

/**
 * Fires before the display of the members loop.
 *
 * @since 1.2.0
 */


echo "<div style=\"font-style:italic;border: 1px solid gray;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;background-color: cornsilk;padding:8px 25px;\"><span style=\"font-weight:bold; font-size:1.4em;\">NOTE: </span> New members that have joined JO but have not yet logged in at least once will NOT be shown in the default sorting options of \"Last Active\" or \"Newest Registered.\" To include members that have joined but have not yet logged in, please change the \"Order By:\" dropdown to something other than those two options.</div>";

	do_action( 'bp_before_members_loop' );
	?>


	<?php if ( bp_get_current_member_type() ) : ?>
        <p class="current-member-type"><?php bp_current_member_type_message() ?></p>
	<?php endif; ?>

	<?php if ( bp_has_members( bp_ajax_querystring( 'members' ) ) ) : ?>


        <div id="pag-top" class="pagination">

            <div class="pag-count" id="member-dir-count-top">

				<?php bp_members_pagination_count(); ?>

            </div>

            <div class="pagination-links" id="member-dir-pag-top">

				<?php bp_members_pagination_links(); ?>

            </div>

        </div>

		<?php

		/**
		 * Fires before the display of the members list.
		 *
		 * @since 1.1.0
		 */
		do_action( 'bp_before_directory_members_list' ); ?>

        <ul id="members-list" class="item-list" aria-live="assertive"
            aria-relevant="all">


			<?php

			while ( bp_members() ) : bp_the_member(); ?>

               <?php if(bp_get_member_user_id() > 2) { ?>
                <li <?php bp_member_class(); ?>>
                    <div class="item-avatar">
                        <a href="<?php bp_member_permalink(); ?>"><?php bp_member_avatar(); ?></a>
                    </div>

                    <div class="item">
                        <div class="item-title">

							<?php
							$firstName   = xprofile_get_field_data( 3,
								bp_get_member_user_id() );
							$lastName    = xprofile_get_field_data( 1,
								bp_get_member_user_id() );
							$branchField = xprofile_get_field_data( 6,
								bp_get_member_user_id() );
							$BR          = substr( $branchField, 0,
								strpos( $branchField, " - " ) );

							$yeargroupField = xprofile_get_field_data( 21,
								bp_get_member_user_id() );
							$YG             = substr( $yeargroupField,
								( strlen( $yeargroupField ) - 2 ),
								( strlen( $yeargroupField ) - 1 ) );

							$currStatus = xprofile_get_field_data( 32,
								bp_get_member_user_id() );
							$currPost   = xprofile_get_field_data( 77,
								bp_get_member_user_id() );
							$component  = xprofile_get_field_data( 26,
								bp_get_member_user_id() );
							$EEaddress  = xprofile_get_field_data( 5,
								bp_get_member_user_id() );
							$registeredDate = bp_get_member_registered(array('relative' => false));
							$lastActive = bp_get_member_last_active(array('relative' => false));

							//$YG = substr($yeargroupField, (strlen($yeargroupField) -2), (strlen($yeargroupField) -1));

							echo "<a href=\"" . bp_get_member_permalink()
							     . "\"><strong>" . $firstName . " " . $lastName
							     . "</strong> (@"
							     . bp_get_member_user_nicename() . ")</a><br/>";
							if(true || current_user_can('administrator')){

							    echo "<strong>Joined:</strong> " . $registeredDate . " <br/><strong>Active:</strong> " . $lastActive . " (". bp_get_member_last_active() . ")<br/>";
                            }


							echo $BR . ", '" . $YG . " (" . $component
							     . ")<br/>";
							echo "<strong>Status:</strong> " . $currStatus
							     . "<br/>";
							echo "<strong>Location:</strong> " . $currPost
							     . "<br/>";
							?>
							<?php if ( bp_get_member_latest_update() ) : ?>

                                <strong>Latest Status:</strong>
                                <span class="update"> <?php bp_member_latest_update(); ?></span>
                                <br/>

							<?php endif; ?>
							<?php
							echo "<a href=\"mailto:" . $EEaddress . "\">Email "
							     . $firstName . "</a>";
							?>

                        </div>

						<?php
						/*
						 *
						 <div class="item-meta"><span class="activity" data-livestamp="<?php bp_core_iso8601_date( bp_get_member_last_active( array( 'relative' => true ) ) ); ?>"><?php bp_member_last_active(); ?></span></div>

						*/
						?>

						<?php

						/**
						 * Fires inside the display of a directory member item.
						 *
						 * @since 1.1.0
						 */
						do_action( 'bp_directory_members_item' ); ?>

						<?php
						/***
						 * If you want to show specific profile fields here you can,
						 * but it'll add an extra query for each member in the loop
						 * (only one regardless of the number of fields you show):
						 *
						 * bp_member_profile_data( 'field=the field name' );
						 */
						?>
                    </div>

                    <div class="action">

						<?php

						/**
						 * Fires inside the members action HTML markup to display actions.
						 *
						 * @since 1.1.0
						 */
						do_action( 'bp_directory_members_actions' ); ?>

                    </div>

                    <div class="clear"></div>
                </li>
                <?php } ?>
			<?php endwhile; ?>

        </ul>

		<?php

		/**
		 * Fires after the display of the members list.
		 *
		 * @since 1.1.0
		 */
		do_action( 'bp_after_directory_members_list' ); ?>

		<?php bp_member_hidden_fields(); ?>

        <div id="pag-bottom" class="pagination">

            <div class="pag-count" id="member-dir-count-bottom">

				<?php bp_members_pagination_count(); ?>

            </div>

            <div class="pagination-links" id="member-dir-pag-bottom">

				<?php bp_members_pagination_links(); ?>

            </div>

        </div>

	<?php else: ?>

        <div id="message" class="info">
            <p><?php _e( "Sorry, no members were found.", 'buddypress' ); ?></p>
        </div>

	<?php endif; ?>

	<?php

	/**
	 * Fires after the display of the members loop.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_after_members_loop' );


?>

