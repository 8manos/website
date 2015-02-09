			<div class="section-name contact-header is-fixed color-bg">
				<h1 class="footer-toggle">¡Yo Quiero!</h1>
				<button type="button" class="footer-close icon-close"></button>
			</div>
		</div>

		<div class="ugly-filler"></div>

		<footer role="contentinfo" class="contact-footer">

		<div class="inner-footer">
			<div class="footer-form">
				<?php echo kc_get_option( 'manos', 'contact_fields', 'intro' ); ?>
				<?php echo do_shortcode( kc_get_option( 'manos', 'contact_fields', 'form' ) ); ?>
			</div>
			<div class="footer-block">
				<div class="contact-block">
					<?php echo kc_get_option( 'manos', 'contact_fields', 'address' ); ?>
				</div>
				<div class="contact-block social">
					<h4>8manos en la red</h4>
					<ul class="contact-links">
						<?php
						$links = kc_get_option( 'manos', 'contact_fields', 'links' );
						foreach ($links as $link) {
							echo '<li><a href="'.$link['link_url'].'" class="icon-'.$link['link_type'].'" target="_blank">'.$link['link_type'].'</a></li>';
						}
						?>
					</ul>
				</div>
			</div>
		</div>
		<div class="otto">
			<svg id="otto" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="128px" height="113px" viewBox="0 0 128 113" enable-background="new 0 0 128 113"
			xml:space="preserve">
				<defs>
				</defs>
				<path d="M41.2,86.6c-0.2-0.4-0.5-0.7-0.8-0.8c-0.3-0.1-0.5-0.1-0.7-0.1c-0.2,0-0.7,0-1.2,1.8c-0.2,0.9-0.3,1.5-0.1,1.9
				c0.2,0.3,0.5,0.4,0.9,0.5c0.1,0,0.2,0,0.3,0c0.7,0,1.4-0.7,1.7-1.7C41.4,87.6,41.4,87,41.2,86.6 M40.4,87.9
				c-0.1,0.3-0.3,0.7-0.6,0.9c-0.1,0.1-0.2,0.1-0.2,0.1c0,0-0.1,0-0.1,0c-0.2-0.1-0.3-0.3-0.3-0.4c0-0.1,0.1-0.2,0.3-0.3
				c0.1-0.1,0.2-0.3,0.2-0.4s0-0.4,0-0.5c-0.1-0.1-0.1-0.2-0.1-0.4c0.1-0.2,0.3-0.3,0.4-0.3c0.2,0.1,0.4,0.3,0.4,0.7
				C40.4,87.5,40.4,87.7,40.4,87.9"/>
				<path d="M39,90.7c-0.4-0.3-0.6-0.6-0.9-0.6c-0.4,0-0.9,0.3-1.5,1c-0.6,0.6-1,1.2-1,1.5c0,0.3,0.2,0.6,0.5,0.9
				c0.3,0.3,0.6,0.3,0.8,0.3c0.5,0,1.1-0.3,1.6-0.7c0.4-0.4,0.6-0.9,0.7-1.3C39.4,91.4,39.3,91,39,90.7 M38,92.4
				c-0.2,0.2-0.6,0.4-0.9,0.4h0c-0.2,0-0.3,0-0.3-0.1c-0.1-0.1-0.1-0.3,0-0.5c0.1-0.1,0.2-0.1,0.4-0.1c0.1,0,0.3-0.1,0.4-0.2
				c0.1-0.1,0.2-0.3,0.2-0.4c0-0.1,0-0.3,0.1-0.4c0.1-0.1,0.4-0.1,0.5,0c0.1,0.1,0.1,0.1,0.1,0.3C38.4,91.8,38.2,92.2,38,92.4"/>
				<path d="M35.4,93.9c-0.3-0.7-0.5-0.9-1-0.9c-0.4,0-0.9,0.2-1.6,0.5c-1.9,0.9-1.7,1.4-1.3,2.2c0.2,0.5,0.7,0.7,1.4,0.7
				c0.4,0,0.8-0.1,1.1-0.3C35.1,95.7,35.7,94.7,35.4,93.9 M33.7,95.3c-0.1,0-0.4,0.2-0.8,0.2c-0.3,0-0.5-0.1-0.6-0.3
				c-0.1-0.2,0-0.4,0.2-0.5c0.1-0.1,0.3,0,0.4,0c0.1,0,0.4,0,0.5-0.1c0.1-0.1,0.3-0.2,0.4-0.3c0-0.1,0.1-0.3,0.2-0.3
				c0.2-0.1,0.4,0,0.5,0.2C34.7,94.7,33.8,95.2,33.7,95.3"/>
				<path d="M30.5,95.7c-0.1-0.9-0.1-1.4-1.6-1.4c-0.2,0-0.5,0-0.7,0c-2.2,0.2-2.2,0.8-2.1,1.8c0.1,0.8,1,1.4,2.1,1.4c0.1,0,0.2,0,0.3,0
				C29.7,97.3,30.6,96.5,30.5,95.7 M29.4,95.9c-0.2,0.3-0.8,0.5-1.1,0.5c0,0-0.1,0-0.1,0c-0.3,0-0.8-0.1-1-0.3C27.1,96,27,95.9,27,95.8
				c0-0.2,0.1-0.4,0.4-0.4c0.2,0,0.3,0.1,0.4,0.2c0.1,0,0.4,0.1,0.5,0.1c0.2,0,0.4-0.1,0.5-0.2c0.1-0.1,0.2-0.2,0.3-0.2
				c0.2,0,0.4,0.1,0.4,0.4C29.5,95.7,29.5,95.8,29.4,95.9"/>
				<path d="M23.7,92.9c-0.7-0.3-1.3-0.5-1.7-0.5c-0.6,0-0.8,0.3-1.1,1c-0.2,0.4-0.1,0.8,0.1,1.3c0.3,0.5,0.7,0.9,1.3,1.2
				c0.4,0.2,0.9,0.3,1.3,0.3c0.7,0,1.3-0.3,1.6-0.8C25.6,94.4,25.8,93.9,23.7,92.9 M24.3,94.8c-0.1,0.2-0.3,0.3-0.7,0.3
				c0,0-0.1,0-0.1,0c-0.3,0-0.5-0.1-0.7-0.2c-0.3-0.1-0.8-0.5-0.9-0.9c-0.1-0.2,0-0.3,0-0.4c0.1-0.2,0.3-0.3,0.6-0.2
				c0.1,0.1,0.2,0.2,0.2,0.4c0.1,0.1,0.3,0.3,0.4,0.4c0.2,0.1,0.4,0.1,0.6,0.1c0.1-0.1,0.3-0.1,0.4-0.1C24.3,94.3,24.4,94.6,24.3,94.8"
				/>
				<path d="M19.3,89.6c-0.8-0.8-1.4-1.2-1.9-1.2c-0.4,0-0.7,0.3-1.1,0.7c-0.7,0.7-0.4,2,0.5,3c0.6,0.6,1.3,0.9,2,0.9
				c0.3,0,0.7-0.1,1-0.4c0.4-0.4,0.7-0.7,0.7-1.1C20.6,91.1,20.1,90.5,19.3,89.6 M19.2,91.7c-0.1,0.1-0.3,0.2-0.4,0.2
				c-0.5,0-1.1-0.4-1.2-0.6c-0.2-0.2-0.3-0.4-0.4-0.7c-0.2-0.4-0.2-0.7,0-0.9c0.2-0.2,0.4-0.2,0.6,0C18,89.8,18,90,18,90.2
				c0,0.1,0.2,0.4,0.3,0.5c0.1,0.1,0.4,0.3,0.5,0.3c0.2,0,0.3,0,0.4,0.1C19.4,91.3,19.4,91.5,19.2,91.7"/>
				<path d="M16.8,84.8c-0.7-1.8-1.2-2.1-1.6-2c-0.3,0-0.6,0.1-1,0.2c-0.9,0.3-1.3,1.7-0.8,3.1c0.4,1.1,1.3,1.9,2.2,1.9
				c0.2,0,0.3,0,0.4-0.1c0.6-0.2,1-0.4,1.1-0.7C17.3,86.8,17.2,86,16.8,84.8 M15.8,86.8c-0.1,0-0.1,0-0.2,0c-0.6,0-1.1-0.9-1.1-1.1
				c-0.1-0.2-0.4-1.4,0.3-1.7c0.2-0.1,0.5,0,0.6,0.3c0.1,0.2,0,0.3-0.1,0.5c0,0.1,0,0.5,0.1,0.6c0.1,0.2,0.3,0.4,0.4,0.5
				c0.2,0,0.3,0.1,0.4,0.3C16.1,86.4,16,86.7,15.8,86.8"/>
				<path d="M15,76.2c0,0-0.1,0-0.1,0c-1,0-1.9,1.1-2,2.6c-0.1,1.5,0.6,2.8,1.7,2.9c0.2,0,0.4,0,0.6,0c0.6,0,1.3,0,1.5-2.6
				C17,76.4,16.2,76.3,15,76.2 M15.3,78.3c-0.1,0.1-0.2,0.4-0.2,0.6c0,0.2,0.1,0.5,0.1,0.7c0.1,0.1,0.2,0.2,0.2,0.4
				c0,0.3-0.2,0.4-0.5,0.4h0c-0.7,0-0.8-1.3-0.8-1.6c0-0.2,0.3-1.5,1-1.4c0.3,0,0.5,0.3,0.4,0.5C15.6,78.1,15.5,78.3,15.3,78.3"/>
				<path d="M18.9,70.1c-0.3-0.2-0.6-0.3-0.9-0.3c-0.9,0-1.8,0.6-2.4,1.5C15.2,72,15,72.7,15,73.4c0,0.6,0.3,1.1,0.7,1.4
				c0.5,0.4,0.9,0.6,1.2,0.6c0.5,0,1.2-0.6,2-1.8C20.6,71.3,19.9,70.8,18.9,70.1 M18.6,72c-0.1,0.2-0.3,0.2-0.5,0.2
				c-0.1,0.1-0.4,0.3-0.5,0.5c-0.1,0.2-0.2,0.5-0.2,0.6c0.1,0.2,0.1,0.4,0,0.5C17.2,73.9,17,74,16.9,74c-0.1,0-0.2,0-0.3-0.1
				c-0.6-0.4,0-1.7,0.1-1.9c0.1-0.2,0.4-0.4,0.7-0.6c0.4-0.3,0.8-0.3,1-0.2C18.7,71.4,18.7,71.7,18.6,72"/>
				<path d="M25.4,66.1c-0.3-0.5-1-0.9-1.8-0.9c-0.6,0-1.3,0.2-1.9,0.5c-0.7,0.4-1.3,1-1.5,1.7c-0.3,0.6-0.3,1.2,0,1.6
				c0.5,0.8,0.8,1.2,1.3,1.2c0.5,0,1.3-0.3,2.3-0.9c1.2-0.7,1.8-1.3,2-1.8C25.9,67.1,25.7,66.7,25.4,66.1 M24.2,67.7
				c-0.2,0.1-0.4,0.1-0.5,0c-0.2,0-0.5,0.1-0.7,0.2c-0.2,0.1-0.4,0.4-0.5,0.5c0,0.2-0.1,0.4-0.3,0.5c-0.1,0-0.2,0.1-0.3,0.1
				c-0.2,0-0.4-0.1-0.5-0.3c-0.4-0.7,0.7-1.6,1-1.7c0.2-0.1,1.6-0.7,2,0C24.5,67.2,24.5,67.6,24.2,67.7"/>
				<path d="M33,65c-0.1-1.1-1.3-1.9-2.9-1.9c-0.1,0-0.3,0-0.4,0c-1.7,0.2-3,1.3-2.9,2.5c0.1,1.3,0.2,2,2.3,1.9c0.3,0,0.6,0,1,0
				C33.1,67.2,33.1,66.3,33,65 M31.2,66c-0.2,0-0.4-0.1-0.5-0.3c-0.2-0.1-0.5-0.1-0.7-0.1c-0.2,0-0.6,0.2-0.7,0.3
				c-0.1,0.2-0.2,0.3-0.5,0.3c0,0,0,0,0,0c-0.3,0-0.5-0.2-0.5-0.5c0-0.4,0.2-0.7,0.7-0.9c0.3-0.1,0.7-0.3,0.9-0.3c0.3,0,1.8,0,1.8,0.9
				C31.7,65.7,31.5,66,31.2,66"/>
				<path d="M37.9,63.5c-0.4-0.1-0.8-0.1-1.1-0.1c-1.3,0-2.3,0.6-2.6,1.5c-0.2,0.8-0.3,1.2-0.1,1.7c0.3,0.5,1.2,1,2.6,1.4
				c0.8,0.2,1.4,0.3,2,0.3c1.2,0,1.4-0.5,1.7-1.7C40.8,65.3,39.7,63.9,37.9,63.5 M39.1,66.4c-0.1,0.3-0.3,0.4-0.5,0.4c0,0-0.1,0-0.1,0
				c-0.2-0.1-0.4-0.2-0.4-0.4c-0.1-0.1-0.5-0.3-0.7-0.4c-0.2-0.1-0.6,0-0.8,0c-0.1,0.2-0.4,0.2-0.6,0.2c-0.3-0.1-0.5-0.4-0.4-0.7
				c0.2-0.8,1.8-0.6,2-0.6C37.8,65,39.3,65.6,39.1,66.4"/>
				<path d="M45.6,66.3c-0.6-0.4-1.3-0.5-1.9-0.5c-0.8,0-1.5,0.3-1.8,0.9c-0.4,0.7-0.6,1.1-0.5,1.5c0.1,0.6,0.8,1.2,2.1,1.9
				c1,0.6,1.8,0.9,2.3,0.9c0.6,0,0.9-0.4,1.4-1.2C47.8,68.7,47.1,67.1,45.6,66.3 M45.9,69.3c-0.1,0.2-0.3,0.3-0.5,0.3
				c-0.1,0-0.2,0-0.3-0.1c-0.2-0.1-0.3-0.3-0.3-0.5c-0.1-0.1-0.3-0.4-0.5-0.5c-0.2-0.1-0.6-0.2-0.7-0.2c-0.2,0.1-0.4,0.1-0.6,0
				c-0.3-0.1-0.4-0.5-0.2-0.7c0.1-0.1,0.2-0.3,0.4-0.3c0.5-0.1,1.2,0.1,1.6,0.3C45.2,67.6,46.4,68.6,45.9,69.3"/>
				<path d="M51.9,71.3c-0.7-0.7-1.6-1.1-2.4-1.1c-0.4,0-0.8,0.1-1.2,0.5c-0.5,0.5-0.8,0.9-0.8,1.3c0,0.5,0.5,1.3,1.5,2.3
				c1,0.9,1.7,1.4,2.3,1.4c0.5,0,0.9-0.4,1.3-0.8C53.4,74,53.1,72.4,51.9,71.3 M51.5,74.1c-0.1,0.1-0.2,0.2-0.4,0.2
				c-0.1,0-0.3-0.1-0.4-0.2c-0.1-0.1-0.2-0.4-0.1-0.5c0-0.2-0.2-0.5-0.4-0.6c-0.2-0.2-0.5-0.3-0.6-0.4c-0.2,0.1-0.4,0-0.5-0.1
				c-0.2-0.2-0.2-0.5,0-0.7c0.6-0.6,1.7,0.3,1.9,0.5C51.2,72.4,52.1,73.6,51.5,74.1"/>
				<path d="M56.7,77.2c-0.6-1-1.5-1.7-2.4-1.7c-0.3,0-0.5,0.1-0.8,0.2c-0.6,0.3-1,0.6-1.1,1c-0.2,0.5,0.1,1.3,0.8,2.5
				c1.1,1.9,1.8,1.9,2,1.9c0.4,0,0.8-0.2,1.2-0.5c0.4-0.3,0.7-0.7,0.8-1.4C57.3,78.6,57.1,77.9,56.7,77.2 M55.9,79.3
				c-0.1,0.2-0.2,0.3-0.3,0.4c-0.1,0-0.2,0.1-0.3,0.1c-0.2,0-0.3-0.1-0.4-0.3c-0.1-0.2-0.1-0.4,0-0.5c0-0.2-0.1-0.5-0.2-0.7
				c-0.1-0.2-0.4-0.4-0.5-0.5c-0.2,0-0.4-0.1-0.5-0.3c-0.1-0.2-0.1-0.5,0.2-0.7c0.7-0.4,1.5,0.7,1.6,0.9C55.8,78.2,56,78.9,55.9,79.3"
				/>
				<path d="M59.7,83.3c-0.5-1.1-1.4-1.8-2.3-1.8c-0.2,0-0.4,0-0.6,0.1c-0.6,0.3-1,0.5-1.1,0.8c-0.2,0.5,0,1.3,0.5,2.4
				c0.8,1.8,1.4,2,1.8,2c0.3,0,0.7-0.2,1.1-0.3C60.1,86.1,60.4,84.7,59.7,83.3 M58.4,85.6c-0.1,0-0.1,0-0.2,0c-0.2,0-0.4-0.1-0.4-0.3
				c-0.1-0.2-0.1-0.4,0.1-0.5c0-0.1,0-0.5-0.1-0.6c-0.1-0.2-0.3-0.4-0.4-0.5c-0.2,0-0.3-0.1-0.4-0.3c-0.1-0.2,0-0.5,0.2-0.6
				c0.7-0.3,1.4,0.8,1.5,1C58.7,84.1,59.1,85.3,58.4,85.6"/>
				<path d="M62.6,88.9c-0.5-0.9-1.4-1.5-2.2-1.5c-0.3,0-0.5,0.1-0.7,0.2c-0.5,0.3-0.9,0.5-1,0.9c-0.1,0.5,0.1,1.2,0.7,2.3
				c1,1.8,1.6,1.7,1.8,1.7c0.3,0,0.7-0.2,1.1-0.4C63.1,91.6,63.3,90.2,62.6,88.9 M61.5,91.3c-0.1,0-0.2,0.1-0.2,0.1
				c-0.2,0-0.3-0.1-0.4-0.2c-0.1-0.2-0.1-0.3,0-0.5c0-0.1-0.1-0.4-0.2-0.6c-0.1-0.2-0.3-0.4-0.4-0.5c-0.2,0-0.3-0.1-0.4-0.2
				c-0.1-0.2-0.1-0.5,0.2-0.6c0.6-0.4,1.4,0.6,1.5,0.8C61.6,89.7,62.1,90.9,61.5,91.3"/>
				<path d="M65.8,93.8c-0.6-0.8-1.4-1.3-2.2-1.3c-0.3,0-0.6,0.1-0.8,0.2c-0.5,0.4-0.8,0.6-0.9,1c-0.1,0.5,0.2,1.2,0.9,2.2
				c0.8,1.1,1.4,1.6,1.9,1.6c0.4,0,0.7-0.2,1.1-0.5C66.6,96.4,66.6,95,65.8,93.8 M65,96.2c-0.1,0.1-0.2,0.1-0.3,0.1
				c-0.1,0-0.3-0.1-0.4-0.2c-0.1-0.1-0.1-0.3,0-0.5c0-0.1-0.1-0.4-0.2-0.6c-0.1-0.2-0.4-0.3-0.5-0.4c-0.2,0-0.3,0-0.4-0.2
				c-0.1-0.2-0.1-0.5,0.1-0.6c0.6-0.4,1.4,0.5,1.6,0.7C65,94.7,65.6,95.8,65,96.2"/>
				<path d="M69.5,98.2c-0.6-0.6-1.3-0.9-2-0.9c-0.3,0-0.7,0.1-1,0.4c-0.4,0.4-0.7,0.7-0.7,1.1c0,0.5,0.4,1.1,1.2,1.9
				c0.8,0.8,1.4,1.2,1.9,1.2c0.4,0,0.7-0.3,1.1-0.7C70.7,100.5,70.5,99.2,69.5,98.2 M69.1,100.6c-0.1,0.1-0.2,0.1-0.3,0.1
				c-0.1,0-0.2,0-0.3-0.1c-0.1-0.1-0.2-0.3-0.1-0.4c0-0.1-0.2-0.4-0.3-0.5c-0.1-0.1-0.4-0.3-0.5-0.3c-0.2,0-0.3,0-0.4-0.1
				c-0.2-0.2-0.2-0.4,0-0.6c0.5-0.5,1.4,0.3,1.6,0.4c0.2,0.2,0.3,0.4,0.4,0.7C69.3,100.1,69.3,100.4,69.1,100.6"/>
				<path d="M74.6,103.5c-0.1-0.5-0.5-1.1-1-1.5c-0.5-0.4-1.2-0.7-1.8-0.7c-0.5,0-0.9,0.2-1.1,0.5c-0.7,0.8-1.1,1.3,0.7,2.8
				c0.8,0.6,1.4,1,1.8,1c0.4,0,0.6-0.2,1.1-0.7C74.6,104.5,74.7,104,74.6,103.5 M73.4,104.3c-0.1,0.1-0.2,0.1-0.3,0.2
				c-0.1,0-0.2,0-0.3-0.1c-0.1-0.1-0.2-0.3-0.1-0.4c0-0.1-0.2-0.4-0.3-0.5c-0.1-0.1-0.4-0.2-0.5-0.2c-0.1,0.1-0.3,0-0.4-0.1
				c-0.2-0.1-0.2-0.4-0.1-0.6c0.4-0.5,1.4,0.1,1.5,0.3c0.2,0.2,0.6,0.7,0.6,1.1C73.6,104.1,73.5,104.3,73.4,104.3"/>
				<path d="M79.3,106.2c-0.3-0.5-0.7-0.9-1.3-1.1c-0.4-0.2-0.8-0.3-1.2-0.3c-0.7,0-1.2,0.3-1.5,0.8c-0.4,0.9-0.7,1.4,1.4,2.4
				c0.7,0.3,1.2,0.5,1.6,0.5c0.5,0,0.8-0.3,1.1-1C79.5,107.1,79.5,106.6,79.3,106.2 M78.4,107.2c-0.1,0.1-0.2,0.2-0.4,0.2
				c-0.1,0-0.1,0-0.2,0c-0.1-0.1-0.2-0.2-0.2-0.3c-0.1-0.1-0.3-0.3-0.4-0.3c-0.1-0.1-0.4-0.1-0.5-0.1c-0.1,0.1-0.3,0.1-0.4,0
				c-0.2-0.1-0.3-0.3-0.2-0.5c0-0.1,0.1-0.2,0.3-0.3c0.3-0.1,0.9,0,1.2,0.1c0.3,0.1,0.7,0.5,0.8,0.8C78.5,107,78.5,107.1,78.4,107.2"/>
				<path d="M83.9,108.1c-0.3-0.4-0.7-0.8-1.3-1c-0.3-0.1-0.6-0.2-1-0.2c-0.7,0-1.3,0.3-1.5,0.9c-0.2,0.5-0.3,0.8-0.2,1.1
				c0.2,0.4,0.7,0.7,1.6,1c0.6,0.2,1.1,0.3,1.4,0.3c0.6,0,0.8-0.3,1.1-1C84.3,108.9,84.2,108.5,83.9,108.1 M83.2,109.2
				c-0.1,0.2-0.2,0.2-0.3,0.2c0,0-0.1,0-0.1,0c-0.1,0-0.2-0.2-0.2-0.3c-0.1-0.1-0.3-0.2-0.4-0.3c-0.1-0.1-0.4-0.1-0.5,0
				c-0.1,0.1-0.2,0.1-0.4,0.1c-0.2-0.1-0.3-0.3-0.2-0.5c0.2-0.5,1.2-0.3,1.4-0.3C82.5,108.2,83.4,108.6,83.2,109.2"/>
				<path d="M88.5,109.4c-0.3-0.4-0.7-0.7-1.3-0.8c-0.2-0.1-0.5-0.1-0.7-0.1c-0.8,0-1.4,0.4-1.6,0.9c-0.1,0.5-0.2,0.8-0.1,1
				c0.2,0.3,0.7,0.6,1.6,0.8c0.5,0.1,0.9,0.2,1.2,0.2c0.7,0,0.9-0.3,1.1-1C88.8,110.1,88.7,109.7,88.5,109.4 M87.9,110.4
				c0,0.2-0.2,0.3-0.3,0.3c0,0-0.1,0-0.1,0c-0.1,0-0.2-0.1-0.3-0.3c-0.1-0.1-0.3-0.2-0.4-0.2c-0.1,0-0.4,0-0.5,0
				c-0.1,0.1-0.2,0.1-0.3,0.1c-0.2,0-0.3-0.2-0.3-0.4c0-0.1,0.1-0.2,0.2-0.3c0.3-0.2,0.8-0.1,1-0.1c0.3,0.1,0.7,0.3,0.9,0.6
				C87.9,110.2,87.9,110.3,87.9,110.4"/>
				<path d="M93.1,110.3c-0.3-0.4-0.8-0.6-1.4-0.7c-0.2,0-0.3,0-0.5,0c-0.9,0-1.7,0.5-1.8,1.1c-0.2,0.9-0.2,1.4,1.8,1.8
				c0.4,0.1,0.7,0.1,1,0.1c1,0,1.1-0.4,1.3-1.2C93.5,111,93.4,110.6,93.1,110.3 M92.6,111.4c0,0.2-0.2,0.3-0.4,0.3c0,0,0,0-0.1,0
				c-0.1,0-0.2-0.1-0.3-0.3c-0.1-0.1-0.3-0.2-0.5-0.2c-0.1,0-0.4,0-0.5,0c-0.1,0.1-0.2,0.2-0.4,0.1c-0.2,0-0.3-0.2-0.3-0.4
				c0-0.1,0.1-0.2,0.2-0.3c0.3-0.2,0.8-0.2,1.1-0.2c0.3,0,0.8,0.3,1,0.5C92.6,111.2,92.6,111.3,92.6,111.4"/>
				<path d="M97.7,110.4c-0.4-0.3-0.9-0.4-1.4-0.4c-1.1,0-2,0.6-2,1.4c0,0.9,0,1.4,2,1.4c0.9,0,1.5-0.1,1.8-0.4c0.2-0.2,0.2-0.6,0.2-1
				C98.3,111,98.1,110.6,97.7,110.4 M97.1,111.8L97.1,111.8c-0.1,0-0.3,0-0.3-0.1c-0.1-0.1-0.3-0.1-0.5-0.1c-0.1,0-0.4-0.1-0.5,0
				c-0.1,0.1-0.2,0-0.3,0c-0.2,0-0.3,0-0.4-0.2c0-0.1,0-0.1,0.1-0.2c0.2-0.2,0.7-0.3,1-0.3c0.3,0,0.8,0.1,1,0.4
				c0.1,0.1,0.1,0.2,0.1,0.3C97.4,111.6,97.3,111.8,97.1,111.8"/>
				<path d="M103.2,110.4c-0.1-0.7-0.9-1.1-1.8-1.1c-0.2,0-0.3,0-0.5,0c-1.1,0.2-2,1-1.8,1.8c0.1,0.8,0.2,1.2,1.3,1.2
				c0.3,0,0.7,0,1.1-0.1C103.4,111.9,103.3,111.3,103.2,110.4 M102,111.2c-0.1,0-0.3,0-0.4-0.1c-0.1,0-0.4-0.1-0.5,0
				c-0.2,0-0.4,0.1-0.5,0.2c0,0.1-0.1,0.2-0.3,0.3c0,0,0,0-0.1,0c-0.2,0-0.3-0.1-0.4-0.3c-0.1-0.6,0.9-0.9,1.1-0.9
				c0.2,0,1.2-0.1,1.3,0.5C102.3,111,102.2,111.2,102,111.2"/>
				<path d="M108.1,108.4c-0.2-0.5-0.8-0.9-1.6-0.9c-0.3,0-0.7,0.1-1,0.2c-1.2,0.4-1.8,1.4-1.5,2.2c0.3,0.7,0.4,1.1,1.1,1.1
				c0.4,0,0.9-0.1,1.5-0.4c1-0.4,1.5-0.7,1.7-1.1C108.3,109.2,108.2,108.8,108.1,108.4 M107,109.4c-0.1,0.1-0.3,0-0.4-0.1
				c-0.1,0-0.4,0-0.5,0.1c-0.2,0.1-0.4,0.2-0.4,0.3c0,0.1-0.1,0.3-0.3,0.3c0,0-0.1,0-0.1,0c-0.2,0-0.3-0.1-0.4-0.3c0-0.1-0.1-0.2,0-0.4
				c0.1-0.3,0.6-0.6,0.9-0.7c0.3-0.1,0.9-0.2,1.2,0c0.2,0.1,0.2,0.2,0.3,0.3C107.3,109.1,107.2,109.3,107,109.4"/>
				<path d="M113,105.9c-0.2-0.5-0.8-0.7-1.5-0.7c-0.5,0-0.9,0.1-1.4,0.4c-0.6,0.3-1,0.7-1.3,1.3c-0.2,0.5-0.2,0.9,0,1.3
				c0.4,0.7,0.6,1,1.1,1c0.4,0,1-0.2,1.8-0.6c1-0.5,1.5-1,1.6-1.4C113.4,106.7,113.2,106.3,113,105.9 M112,107.1
				c-0.1,0.1-0.3,0.1-0.4,0c-0.1,0-0.4,0.1-0.6,0.1c-0.2,0.1-0.4,0.3-0.4,0.4c0,0.2-0.1,0.3-0.2,0.4c-0.1,0-0.1,0-0.2,0
				c-0.2,0-0.3-0.1-0.4-0.2c-0.3-0.6,0.6-1.2,0.8-1.3c0.2-0.1,1.3-0.5,1.5,0.1C112.3,106.7,112.2,107,112,107.1"/>
				<path d="M117.2,102.5c-0.3-0.3-0.7-0.4-1-0.4c-0.6,0-1.3,0.3-1.8,0.8c-1,0.9-1.2,2.1-0.6,2.8c0.4,0.4,0.7,0.7,1,0.7
				c0.4,0,1-0.4,1.8-1.1c0.8-0.7,1.2-1.3,1.2-1.8C117.8,103.2,117.6,102.9,117.2,102.5 M116.6,103.9c-0.1,0.1-0.3,0.1-0.4,0.1
				c-0.1,0-0.4,0.2-0.5,0.3c-0.1,0.1-0.3,0.4-0.3,0.5c0,0.1,0,0.3-0.1,0.4c-0.1,0.1-0.2,0.1-0.3,0.1c-0.1,0-0.2,0-0.3-0.1
				c-0.4-0.5,0.3-1.3,0.5-1.5c0.2-0.1,1.1-0.8,1.5-0.3C116.8,103.5,116.8,103.8,116.6,103.9"/>
				<path d="M121.1,98.7c-0.1-0.3-0.4-0.6-0.9-0.9c-0.2-0.1-0.4-0.2-0.7-0.2c-0.7,0-1.5,0.5-2,1.3c-0.3,0.5-0.5,1.2-0.5,1.7
				c0,0.5,0.3,0.9,0.6,1.2c0.4,0.3,0.7,0.4,1,0.4c0.5,0,1-0.5,1.7-1.5C120.9,99.8,121.2,99.1,121.1,98.7 M120,99.3
				c-0.1,0.1-0.2,0.2-0.4,0.2c-0.1,0.1-0.3,0.2-0.4,0.4c-0.1,0.1-0.2,0.4-0.2,0.5c0.1,0.1,0.1,0.3,0,0.4c-0.1,0.1-0.2,0.2-0.3,0.2
				c-0.1,0-0.2,0-0.2-0.1c-0.5-0.3-0.1-1.4,0.1-1.5c0.1-0.2,0.8-1.1,1.4-0.7C120,98.9,120.1,99.1,120,99.3"/>
				<path d="M123.4,93.4c-0.2-0.3-0.6-0.5-1-0.6c-0.1,0-0.2-0.1-0.4-0.1c-0.8,0-1.6,0.8-1.9,1.8c-0.4,1.2,0,2.5,0.9,2.7
				c0.3,0.1,0.6,0.2,0.9,0.2c0.2,0,0.8,0,1.4-1.9C123.6,94.5,123.6,93.8,123.4,93.4 M122.5,94.3c0,0.2-0.2,0.3-0.3,0.3
				c-0.1,0.1-0.3,0.3-0.3,0.5c-0.1,0.2-0.1,0.4,0,0.6c0.1,0.1,0.2,0.3,0.1,0.4c-0.1,0.2-0.2,0.3-0.4,0.3c0,0-0.1,0-0.1,0
				c-0.6-0.2-0.4-1.3-0.3-1.5c0.1-0.2,0.5-1.2,1.1-1C122.5,93.9,122.6,94.1,122.5,94.3"/>
				<path d="M125.2,87.5c-0.2-0.6-0.6-1-1.1-1.1c-0.3-0.1-0.7-0.2-1-0.2c-0.3,0-1,0-1.7,2.4c-0.3,1.3-0.4,2.1-0.1,2.6
				c0.2,0.4,0.7,0.5,1.3,0.7c0.1,0,0.2,0,0.4,0c1,0,2-1,2.3-2.3C125.5,88.9,125.5,88.2,125.2,87.5 M124.3,88.3
				c-0.1,0.2-0.2,0.3-0.4,0.4c-0.1,0.1-0.3,0.4-0.3,0.6c-0.1,0.2,0,0.5,0,0.7c0.1,0.1,0.2,0.3,0.1,0.5c-0.1,0.2-0.3,0.4-0.5,0.4
				c0,0-0.1,0-0.1,0c-0.7-0.2-0.6-1.6-0.5-1.8c0.1-0.4,0.4-1,0.8-1.2c0.2-0.1,0.4-0.1,0.5-0.1C124.3,87.7,124.4,88,124.3,88.3"/>
				<path d="M124,79.3L124,79.3c-0.7,0-1.2,0-1.5,0.3c-0.4,0.4-0.6,1.2-0.6,2.6c0,1.4,0.2,2.2,0.6,2.6c0.3,0.3,0.9,0.3,1.5,0.3
				c1.2,0,2.1-1.3,2.1-2.9C126.1,80.6,125.2,79.3,124,79.3 M124.3,83.9L124.3,83.9c-0.3,0-0.6-0.3-0.8-0.8c-0.1-0.3-0.2-0.7-0.2-1
				c0-0.3,0.2-1.7,1-1.7c0.3,0,0.5,0.3,0.5,0.6c0,0.2-0.1,0.4-0.3,0.5c-0.1,0.1-0.2,0.5-0.2,0.7c0,0.2,0.1,0.6,0.2,0.7
				c0.2,0.1,0.3,0.3,0.3,0.5C124.8,83.6,124.5,83.9,124.3,83.9"/>
				<path d="M125.2,74.1c-0.4-1.4-1.4-2.5-2.5-2.5c-0.1,0-0.3,0-0.4,0.1c-0.7,0.2-1.2,0.3-1.4,0.7c-0.3,0.5-0.3,1.4,0.1,2.8
				c0.7,2.6,1.5,2.6,1.9,2.6c0.3,0,0.7-0.1,1-0.2C125.1,77.4,125.7,75.8,125.2,74.1 M123.8,76.4c-0.1,0-0.1,0-0.2,0
				c-0.3,0-0.6-0.2-0.8-0.6c-0.2-0.3-0.4-0.6-0.4-0.9c-0.1-0.3-0.1-0.6-0.1-1c0.1-0.6,0.3-0.9,0.6-1c0.3-0.1,0.6,0.1,0.7,0.4
				c0.1,0.2,0,0.4-0.2,0.5c0,0.2,0,0.5,0,0.8c0.1,0.2,0.3,0.5,0.4,0.7c0.2,0,0.4,0.2,0.4,0.4C124.2,76,124.1,76.3,123.8,76.4"/>
				<path d="M121.7,66.7c-0.7-1.1-1.7-1.9-2.7-1.9c-0.3,0-0.6,0.1-0.8,0.2c-0.7,0.4-1.1,0.7-1.2,1.1c-0.2,0.6,0.1,1.5,0.9,2.8
				c1.3,2.2,2,2.2,2.2,2.2c0.4,0,0.8-0.2,1.3-0.5c0.5-0.3,0.8-0.8,0.9-1.5C122.4,68.4,122.2,67.5,121.7,66.7 M120.9,69.4
				c-0.1,0.1-0.2,0.1-0.3,0.1c-0.2,0-0.5-0.1-0.8-0.4c-0.3-0.2-0.5-0.5-0.7-0.8c-0.1-0.3-0.3-0.6-0.3-1c-0.1-0.6,0-1,0.3-1.2
				c0.3-0.2,0.6-0.1,0.8,0.2c0.1,0.2,0.1,0.4,0,0.6c0,0.2,0.1,0.5,0.2,0.8c0.1,0.2,0.4,0.5,0.5,0.6c0.2,0,0.4,0.1,0.5,0.3
				C121.3,68.9,121.2,69.2,120.9,69.4"/>
				<path d="M116.2,60.6c-0.7-0.6-1.5-0.9-2.3-0.9c-0.6,0-1.1,0.2-1.5,0.6c-0.5,0.6-0.8,1-0.8,1.4c0,0.6,0.6,1.3,1.7,2.3
				c1,0.8,1.8,1.3,2.4,1.3c0.5,0,0.8-0.3,1.4-1C117.9,63.4,117.5,61.7,116.2,60.6 M116.3,63.3c-0.1,0.2-0.3,0.2-0.6,0.2
				c-0.2,0-0.4,0-0.6-0.1c-0.3-0.1-0.7-0.3-0.9-0.5c-0.2-0.2-1.2-1.3-0.7-1.9c0.2-0.2,0.5-0.3,0.8-0.1c0.2,0.1,0.2,0.3,0.2,0.5
				c0.1,0.2,0.3,0.5,0.4,0.6c0.2,0.1,0.5,0.3,0.7,0.3c0.2-0.1,0.4-0.1,0.6,0.1C116.4,62.7,116.4,63,116.3,63.3"/>
				<path d="M108.7,56.9c-0.3-0.1-0.7-0.1-1-0.1c-1.2,0-2.1,0.6-2.4,1.4c-0.2,0.7-0.3,1.2-0.1,1.5c0.3,0.5,1.1,0.9,2.4,1.3
				c0.7,0.2,1.3,0.3,1.8,0.3c1.1,0,1.3-0.5,1.6-1.6C111.3,58.6,110.2,57.3,108.7,56.9 M109.8,59.2c-0.1,0.5-0.7,0.6-1.2,0.6
				c-0.3,0-0.6,0-0.7-0.1c-0.3-0.1-1.6-0.6-1.4-1.4c0.1-0.3,0.4-0.4,0.6-0.4c0.2,0.1,0.3,0.2,0.4,0.4c0.1,0.1,0.4,0.3,0.6,0.4
				c0.2,0.1,0.6,0,0.7,0c0.1-0.1,0.3-0.2,0.5-0.2C109.7,58.6,109.9,58.9,109.8,59.2"/>
				<path d="M103.6,58c-0.2-0.9-1.2-1.5-2.5-1.5c-0.2,0-0.5,0-0.7,0.1c-1.5,0.3-2.6,1.4-2.4,2.5c0.2,1.1,0.3,1.6,1.7,1.6
				c0.4,0,0.9-0.1,1.4-0.2C104,60,103.8,59.2,103.6,58 M101,59.2c-0.1,0-0.2,0-0.4,0c-0.5,0-1.3-0.1-1.4-0.7c0-0.3,0.1-0.5,0.4-0.6
				c0.2,0,0.4,0,0.5,0.2c0.1,0,0.5,0.1,0.7,0.1c0.2,0,0.5-0.2,0.6-0.3c0.1-0.2,0.2-0.3,0.4-0.3c0.3,0,0.5,0.1,0.6,0.4
				C102.5,58.8,101.2,59.2,101,59.2"/>
				<path d="M96.6,59.5c-0.3-0.3-0.7-0.5-1.3-0.5c-0.7,0-1.4,0.3-2,0.8c-1.1,1-1.5,2.4-0.8,3.2c0.5,0.5,0.8,0.9,1.2,0.9
				c0.5,0,1.2-0.4,2.1-1.2C97.8,61,97.3,60.4,96.6,59.5 M95,61.8c-0.1,0.1-0.8,0.5-1.3,0.5c-0.2,0-0.4-0.1-0.5-0.2
				c-0.2-0.2-0.1-0.5,0.1-0.7c0.1-0.1,0.3-0.1,0.5-0.1c0.1,0,0.4-0.2,0.6-0.3c0.2-0.1,0.3-0.4,0.4-0.5c0-0.2,0-0.4,0.2-0.5
				c0.2-0.2,0.5-0.1,0.7,0.1C96,60.7,95.1,61.7,95,61.8"/>
				<path d="M92.2,64.5c-0.1-0.4-0.5-0.6-1.1-0.8c-0.2-0.1-0.4-0.1-0.6-0.1c-0.8,0-1.7,0.7-2.2,1.7C87.8,66.6,88,68,89,68.4
				c0.4,0.2,0.7,0.3,1.1,0.3c0.3,0,0.9-0.2,1.7-1.9C92.2,65.8,92.4,65,92.2,64.5 M90.7,66.4c-0.1,0.2-0.6,1-1.2,1c-0.1,0-0.2,0-0.2-0.1
				C89,67.2,88.9,67,89,66.7c0.1-0.2,0.2-0.3,0.4-0.3c0.1-0.1,0.3-0.3,0.4-0.5c0.1-0.2,0.1-0.5,0.1-0.6c-0.1-0.1-0.1-0.3-0.1-0.5
				c0.1-0.2,0.4-0.3,0.6-0.2C91.1,65,90.8,66.2,90.7,66.4"/>
				<path d="M88.3,69.6c-1,0-1.7,1.1-1.7,2.5c0,1.4,0.8,2.5,1.8,2.5c0.6,0,1,0,1.2-0.3c0.3-0.3,0.5-1.1,0.5-2.2
				C90.1,69.6,89.4,69.6,88.3,69.6 M88.9,72.7c-0.2,0.4-0.4,0.5-0.7,0.5h0c-0.2,0-0.4-0.1-0.4-0.3c0-0.2,0.1-0.3,0.2-0.3
				c0.1-0.1,0.1-0.4,0.1-0.6S88,71.6,88,71.5c-0.1-0.1-0.2-0.3-0.2-0.5c0-0.2,0.2-0.5,0.4-0.5c0.3,0,0.5,0.3,0.7,0.7
				c0.1,0.3,0.2,0.5,0.2,0.8C89,72.2,88.9,72.5,88.9,72.7"/>
				<path d="M90.9,77.4c-0.6-1.6-1.1-1.9-1.5-1.9c-0.3,0-0.6,0.1-0.9,0.2c-0.4,0.1-0.7,0.5-0.8,1c-0.2,0.5-0.1,1.2,0.1,1.8
				c0.4,1,1.2,1.7,2,1.7c0.1,0,0.3,0,0.4-0.1C91.1,79.9,91.7,79.7,90.9,77.4 M89.9,79c-0.1,0.2-0.2,0.3-0.3,0.3c0,0-0.1,0-0.1,0
				c-0.2,0-0.3-0.1-0.4-0.3c-0.1-0.2,0-0.3,0.1-0.4c0-0.1,0-0.4-0.1-0.6c-0.1-0.2-0.2-0.4-0.3-0.5c-0.2,0-0.3-0.1-0.3-0.3
				c-0.1-0.2,0-0.5,0.2-0.5c0.6-0.2,1.1,0.8,1.2,1C90,78.1,90.1,78.7,89.9,79"/>
				<path d="M93,81.6c-0.7-0.6-1.3-0.9-1.7-0.9c-0.4,0-0.6,0.2-1,0.7c-0.6,0.7-0.2,1.8,0.7,2.6c0.5,0.4,1.1,0.7,1.7,0.7
				c0.4,0,0.8-0.2,1.1-0.4C94.4,83.5,94.7,83,93,81.6 M92.9,83.7c-0.1,0.1-0.2,0.1-0.3,0.1c-0.1,0-0.2,0-0.3-0.1
				c-0.1-0.1-0.2-0.3-0.1-0.4c0-0.1-0.2-0.3-0.3-0.4c-0.1-0.1-0.4-0.2-0.5-0.2c-0.1,0.1-0.3,0-0.4-0.1c-0.2-0.1-0.2-0.4-0.1-0.6
				c0.2-0.2,0.4-0.2,0.8-0.1c0.2,0.1,0.5,0.2,0.6,0.3c0.2,0.2,0.6,0.7,0.6,1C93,83.6,93,83.7,92.9,83.7"/>
				<path d="M98.6,85.4c-0.2-0.4-0.7-0.7-1.6-1c-0.6-0.2-1.1-0.3-1.4-0.3c-0.6,0-0.8,0.3-1,1c-0.1,0.4-0.1,0.8,0.2,1.2
				c0.3,0.4,0.7,0.8,1.3,1c0.3,0.1,0.6,0.2,1,0.2c0.7,0,1.3-0.3,1.5-0.9C98.6,85.9,98.7,85.6,98.6,85.4 M97.5,86.3
				c-0.1,0.2-0.2,0.2-0.3,0.2c0,0-0.1,0-0.1,0c-0.1,0-0.2-0.2-0.2-0.3c-0.1-0.1-0.3-0.2-0.4-0.3c-0.1-0.1-0.4-0.1-0.5,0
				c-0.1,0.1-0.2,0.1-0.4,0.1c-0.2-0.1-0.3-0.3-0.2-0.5c0.2-0.5,1.2-0.3,1.4-0.3C96.8,85.3,97.7,85.7,97.5,86.3"/>
				<path d="M103,85.8c-0.2-0.3-0.8-0.4-1.8-0.4c-0.9,0-1.5,0.1-1.8,0.4c-0.2,0.2-0.2,0.6-0.2,1c0,0.4,0.2,0.7,0.5,1
				c0.4,0.3,0.9,0.4,1.4,0.4c1.1,0,2-0.6,2-1.4C103.2,86.3,103.2,86,103,85.8 M102,87.3L102,87.3c-0.1,0-0.3-0.1-0.3-0.2
				c-0.1-0.1-0.3-0.1-0.5-0.1c-0.1,0-0.4,0.3-0.5,0.3c-0.1,0.1-0.2,0.4-0.3,0.4h0c-0.2,0-0.3-0.4-0.4-0.6c0-0.1,0-0.3,0.1-0.4
				c0.2-0.2,0.7-0.4,1-0.4c0.3,0,0.8,0.1,1,0.3c0.1,0.1,0.1,0.3,0.1,0.3C102.4,87.1,102.2,87.3,102,87.3"/>
				<polygon points="36.6,32.1 36.6,32.1 36.6,32.1 "/>
				<path d="M107.7,84.2c-0.3-0.6-0.5-0.8-0.9-0.8c-0.4,0-0.9,0.2-1.6,0.6c-0.8,0.5-1.3,0.9-1.4,1.3c-0.1,0.3,0.1,0.6,0.3,1
				c0.2,0.4,0.7,0.6,1.2,0.6c0.4,0,0.9-0.1,1.3-0.4C107.7,86,108.1,84.9,107.7,84.2 M106.9,85.3c-0.1,0.1-0.3,0.1-0.4,0
				c-0.1,0-0.4,0.1-0.5,0.1c-0.1,0.1-0.3,0.3-0.4,0.4c0,0.1-0.1,0.3-0.2,0.3c-0.1,0-0.1,0-0.2,0c-0.1,0-0.2-0.1-0.3-0.2
				c-0.1-0.2-0.1-0.5,0.2-0.7c0.1-0.2,0.3-0.3,0.5-0.4c0.2-0.1,0.8-0.3,1.1-0.2c0.2,0,0.3,0.1,0.3,0.2C107.1,85,107.1,85.2,106.9,85.3"
				/>
				<path d="M36.6,32.1c0.2,0.4,0.5,0.8,0.8,1.2c1,1.5,2.8,4.5,4.5,6.9c-0.1-0.8-0.2-1.8-0.2-2.7c0-2.4,0.4-4.4,1.3-6
				c0.9-1.6,1.9-2.9,3.2-3.9c1.3-1,2.6-1.6,4-2c1.4-0.4,2.7-0.6,3.8-0.6c1.3,0,2.5,0.2,3.6,0.6c1.1,0.4,2.1,0.9,3.1,1.6
				c0.9,0.6,1.8,1.4,2.5,2.2c0.7,0.8,1.3,1.6,1.9,2.4c0.5-0.8,1.1-1.6,1.9-2.4c0.7-0.8,1.5-1.6,2.5-2.2c0.9-0.6,2-1.2,3.1-1.6
				c1.1-0.4,2.3-0.6,3.6-0.6c1.1,0,2.4,0.2,3.8,0.6c1.4,0.4,2.8,1.1,4,2c1.3,1,2.3,2.3,3.2,3.9c0.9,1.6,1.3,3.6,1.3,6
				c0,0.3,0,0.6,0,0.9c1.2-5.1,1.5-10.8,0.2-16.6C86.6,13.1,79,3.6,67.8,0.9C56.6-1.9,40.1,1.3,35.5,16.5
				C33.6,22.7,34.3,28.1,36.6,32.1C36.6,32.1,36.6,32.1,36.6,32.1"/>
				<path d="M121.9,85.6C121.9,85.6,121.9,85.6,121.9,85.6c-0.7-0.7-1-1.7-1-3.5c0-1.8,0.3-2.8,0.9-3.4c0,0,0,0,0,0
				c-0.8-0.4-1.4-1.5-1.9-3.2c-0.4-1.5-0.4-2.6-0.2-3.3c-1-0.1-1.9-1-2.9-2.7c-0.8-1.4-1.1-2.5-1.1-3.2c-0.9,0-1.8-0.5-3.1-1.5
				c-1.3-1.1-1.9-2-2.1-2.7c-0.3,0.1-0.7,0.2-1.2,0.2c-0.6,0-1.2-0.1-2.1-0.3c-1.7-0.5-2.7-1-3.1-1.8c0,0,0,0,0,0
				c-0.5,0.5-1.3,1-2.9,1.3c-0.6,0.1-1.2,0.2-1.6,0.2c-0.7,0-1.3-0.1-1.6-0.4c-0.2,0.6-0.7,1.4-1.8,2.3c-1.1,0.9-2,1.4-2.7,1.4
				c-0.2,0-0.3,0-0.4-0.1c0,0.6-0.2,1.4-0.6,2.4c-0.7,1.4-1.3,2.2-2,2.4c0.3,0.5,0.5,1.3,0.5,2.4c0,1.6-0.3,2.5-0.8,2.9
				c0.6,0.3,1.1,1,1.5,2.2c0.5,1.4,0.5,2.3,0.2,2.8c0.5,0.1,1,0.5,1.7,1c1.2,1,1.5,1.7,1.5,2.3c0.1,0,0.3,0,0.4,0c0.5,0,1,0.1,1.7,0.4
				c1.2,0.4,1.8,0.9,2.1,1.4c0,0,0,0,0,0c0.4-0.3,1.1-0.4,2-0.4c0.9,0,1.5,0.1,1.9,0.3c0.2-0.5,0.7-1.1,1.7-1.6
				c0.8-0.5,1.4-0.7,1.9-0.7c0.5,0,0.8,0.2,1.1,0.5c-0.4-1.6-1.7-1.9-1.7-1.9c-2.3-0.4-4.7-2.8-3.9-6.1s5.2-3.6,6.3-3.2
				c0,0,7.2,2.1,6.4,10.7c-0.3,3.3-3.2,14.4-18.5,12.6c0,0-11.2-1.7-16.9-11.3C75,76,75.7,69.1,77.3,63.7c0.6-2,2.1-5,2.6-5.9
				c2.1-3.4,4.7-7.9,6.6-13.3c-0.8,1.2-1.6,2.2-2.6,3c-1.3,1-2.6,1.6-4,2c-1.4,0.4-2.7,0.6-3.8,0.6c-1.3,0-2.5-0.2-3.6-0.6
				c-1.1-0.4-2.1-0.9-3.1-1.6c-0.9-0.6-1.8-1.4-2.5-2.2c-0.7-0.8-1.3-1.6-1.9-2.4c-0.5,0.8-1.1,1.6-1.9,2.4c-0.7,0.8-1.5,1.5-2.5,2.2
				c-0.9,0.6-2,1.2-3.1,1.6c-1.1,0.4-2.3,0.6-3.6,0.6c-1.1,0-2.4-0.2-3.8-0.6c-1.4-0.4-2.8-1.1-4-2c-0.7-0.5-1.3-1.1-1.8-1.8
				c-0.3,0.9-1,1.3-1.9,1.3c-5.5-0.9-12.7-0.9-20.5,2.2c-17.8,7-21,22.2-21,22.2c-2.6,10,1.7,18.2,1.7,18.2c2.7,5.5,6.8,13,17.6,15.9
				c10.8,2.9,19.9-3.9,19.9-3.9c12.1-9.6,6.2-19.5,4.2-22c-5.4-6.7-12.1-3.1-12.1-3.1c-1.1,0.5-4,3.8-2.2,6.8c1.8,2.9,5.1,2.9,7.1,1.6
				c0,0,1.2-0.7,2.5,0.1c0,0,0,0,0.1,0c0.3,0,0.6,0.1,0.9,0.2c0.6,0.2,1,0.6,1.3,1.2c0.2,0.6,0.3,1.3,0.1,2c-0.2,0.9-0.8,1.6-1.4,1.9
				c-0.2,0.3-0.3,0.5-0.6,0.8c0,0.2,0.1,0.5,0,0.8c-0.1,0.6-0.4,1.2-0.9,1.7c-0.6,0.6-1.3,0.9-2.1,0.9c-0.3,0-0.5,0-0.7-0.1
				c0,0-0.1,0.1-0.2,0.1c-0.1,0.9-0.7,1.8-1.8,2.3c-0.5,0.2-1,0.3-1.5,0.3c-0.7,0-1.3-0.2-1.7-0.6c0,0-0.1,0-0.1,0
				c-0.4,0.9-1.4,1.6-2.6,1.7c-0.1,0-0.2,0-0.3,0c-1.4,0-2.6-0.8-2.8-1.8c-0.4,0.4-1.1,0.6-1.8,0.6c-0.5,0-1.1-0.1-1.6-0.4
				c-0.8-0.3-1.4-0.9-1.7-1.6c-0.2-0.4-0.3-0.8-0.3-1.1c-0.1-0.1-0.2-0.2-0.3-0.2c-0.2,0.1-0.5,0.1-0.7,0.1c-0.9,0-1.8-0.4-2.6-1.2
				c-1.2-1.2-1.5-2.8-0.8-3.9c-1.2,0-2.4-1.1-2.9-2.5c-0.6-1.6-0.2-3.2,0.9-4c0-0.1,0-0.2,0-0.3c-0.9-0.7-1.5-2-1.4-3.5
				c0.2-1.8,1.3-3.3,2.8-3.4c-0.4-0.5-0.7-1.1-0.7-1.9c0-0.9,0.3-1.8,0.8-2.7c0.8-1.2,2-1.9,3.2-1.9c0.4,0,0.7,0.1,1,0.2
				c-0.2-0.6-0.1-1.3,0.2-2c0.4-0.9,1.1-1.6,2-2.2c0.8-0.4,1.6-0.7,2.4-0.7c0.9,0,1.6,0.3,2.2,0.7c0.3-1.5,1.8-2.7,3.8-2.9
				c0.2,0,0.3,0,0.5,0c1.5,0,2.9,0.7,3.5,1.7c0.6-0.9,1.8-1.5,3.2-1.5c0.5,0,1,0.1,1.4,0.2c1.7,0.4,3,1.6,3.3,2.9
				c0.6-0.4,1.3-0.7,2.1-0.7c0.8,0,1.7,0.2,2.5,0.7c1.6,0.9,2.5,2.6,2.3,4c0.3-0.1,0.7-0.2,1.1-0.2c1.1,0,2.2,0.5,3.1,1.4
				c1.2,1.2,1.6,2.8,1.3,4c0.1,0,0.2,0,0.4,0c1.2,0,2.5,0.8,3.3,2.2c0.5,0.9,0.7,1.8,0.6,2.7c-0.1,0.4-0.2,0.8-0.4,1.2
				c1.1,0.2,2.2,1,2.8,2.3c0.6,1.3,0.6,2.7,0,3.6c1.1,0.1,2.1,0.8,2.8,1.9c0.6,1,0.7,2.2,0.4,3.1c1,0.1,2.1,0.7,2.8,1.7
				c0.7,1,0.9,2.2,0.7,3.2c0.1,0,0.2,0,0.3,0c0.9,0,1.8,0.4,2.6,1.2c0.9,0.9,1.3,2,1.1,3c0.2,0,0.4-0.1,0.6-0.1c0.8,0,1.6,0.3,2.3,0.9
				c0.6,0.5,1.1,1.2,1.3,1.9c0.1,0.3,0.1,0.7,0,1c0.4-0.2,0.8-0.3,1.4-0.3c0.5,0,1,0.1,1.5,0.3c0.7,0.3,1.3,0.9,1.6,1.5
				c0.2,0.3,0.2,0.6,0.3,0.8c0.4-0.3,0.9-0.4,1.5-0.4c0.4,0,0.8,0.1,1.2,0.2c0.7,0.3,1.3,0.7,1.6,1.2c0.1,0.2,0.2,0.4,0.3,0.6
				c0.4-0.3,1-0.5,1.6-0.5c0.3,0,0.6,0,0.9,0.1c0.7,0.2,1.3,0.5,1.6,1c0.1,0.2,0.2,0.3,0.3,0.5c0.5-0.4,1.1-0.6,1.9-0.6
				c0.2,0,0.4,0,0.6,0.1c0.7,0.1,1.4,0.5,1.8,0.9c0.1,0.2,0.3,0.3,0.4,0.5c0.5-0.6,1.3-1,2.3-1c0.7,0,1.4,0.2,1.9,0.6
				c0.1,0.1,0.2,0.2,0.3,0.3c0.4-0.7,1.2-1.3,2.2-1.5c0.2,0,0.4-0.1,0.6-0.1c0.7,0,1.3,0.2,1.8,0.5c0.2-0.9,1-1.7,2.1-2.1
				c0.4-0.2,0.8-0.2,1.3-0.2c0.5,0,1,0.1,1.4,0.4c0-0.2,0.1-0.5,0.2-0.7c0.3-0.7,0.9-1.2,1.6-1.6c0.6-0.3,1.2-0.4,1.7-0.4
				c0.4,0,0.8,0.1,1.2,0.2c0.1-0.8,0.5-1.6,1.2-2.3c0.7-0.6,1.5-1,2.4-1c0.1,0,0.1,0,0.2,0c-0.1-0.2-0.1-0.4-0.1-0.6
				c-0.1-0.7,0.2-1.5,0.6-2.2c0.6-1,1.6-1.6,2.5-1.7c-0.3-0.7-0.4-1.6-0.1-2.5c0.3-0.9,0.9-1.7,1.6-2.1c-0.1-0.1-0.3-0.3-0.4-0.4
				c-0.4-0.7-0.4-1.8,0-3.4C120.8,87,121.3,86.1,121.9,85.6 M65.9,59c-1,0.9-5.7,3-8.5,2.3c-2.9-0.8-4.2-4.5-2.6-5.4
				c0.8-0.5,6.6,2.5,10.7,1.7C65.5,57.6,66.9,58.1,65.9,59"/>
				<path d="M54.3,44.1c1.6,0,3-0.5,4.3-1.5c1.3-1,2.5-2.8,3.7-5.3c-1.2-2.5-2.4-4.2-3.7-5.3c-1.3-1-2.7-1.5-4.3-1.5
				c-1.6,0-3.1,0.6-4.4,1.7c-1.3,1.1-1.9,2.8-1.9,5.1c0,2.2,0.6,3.9,1.9,5.1C51.2,43.6,52.7,44.1,54.3,44.1"/>
				<path d="M75.7,44.1c1.6,0,3.1-0.6,4.4-1.7c1.3-1.1,1.9-2.8,1.9-5.1c0-2.2-0.6-3.9-1.9-5.1c-1.3-1.1-2.7-1.7-4.4-1.7
				c-1.6,0-3,0.5-4.3,1.5c-1.3,1-2.5,2.8-3.7,5.3c1.2,2.5,2.4,4.2,3.7,5.3C72.7,43.6,74.1,44.1,75.7,44.1"/>
			</svg>
		</div>
	</footer>

	<?php
		get_template_part('template', 'pages');
		get_template_part('template', 'team');
		get_template_part('template', 'persons');
		get_template_part('template', 'portfolio');
		get_template_part('template', 'labs');
		get_template_part('template', 'friends');
		get_template_part('template', 'guide');
	?>

	<?php wp_footer(); ?>

	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-18501899-1', 'auto');
		ga('send', 'pageview');
	</script>
	<script src="<?php bloginfo('template_directory'); ?>/js/init.js"></script>
	<script src="<?php bloginfo('template_directory'); ?>/js/app.js"></script>
	<script src="<?php bloginfo('template_directory'); ?>/js/models.js"></script>
	<script src="<?php bloginfo('template_directory'); ?>/js/collections.js"></script>
	<script src="<?php bloginfo('template_directory'); ?>/js/routers.js"></script>
	<script src="<?php bloginfo('template_directory'); ?>/js/views.js"></script>
	<script src="<?php bloginfo('template_directory'); ?>/js/time.js"></script>
	<script src="<?php bloginfo('template_directory'); ?>/js/contrast.js"></script>
</body>
</html>