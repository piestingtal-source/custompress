<?php if (!defined('ABSPATH')) die('No direct access allowed!');

//var_dump($this->all_capabilities('jbp_job') );

if ( is_network_admin() )
$post_types = get_site_option('ct_custom_post_types');
else
$post_types = $this->post_types;

$post_types = array();
if(is_multisite()) {

	if($this->display_network_content || is_network_admin() ){
		$cf = get_site_option('ct_custom_post_types');
		$post_types['net'] = (empty($cf)) ? array() : $cf;
	}
	if($this->enable_subsite_content_types && ! is_network_admin() ){
		$cf = get_option('ct_custom_post_types');
		$post_types['local'] = (empty($cf)) ? array() : $cf;
	}
} else {
	$cf = get_option('ct_custom_post_types');
	$post_types['local'] = (empty($cf)) ? array() : $cf;
}

$this->render_admin('update-message');

?>


<form action="#" method="post" class="ct-form-single-btn">
	<input type="submit" class="button-secondary" name="redirect_add_post_type" value="<?php esc_attr_e('Beitragstyp hinzufügen', $this->text_domain); ?>" />
</form>

<table class="widefat">
	<thead>
		<tr>
			<th><?php esc_html_e('Beitragstyp', $this->text_domain); ?></th>
			<th><?php esc_html_e('Name', $this->text_domain); ?></th>
			<th><?php esc_html_e('Beschreibung', $this->text_domain); ?></th>
			<th><?php esc_html_e('Menü Icon', $this->text_domain); ?></th>
			<th><?php esc_html_e('Unterstützt', $this->text_domain); ?></th>
			<th><?php esc_html_e('Berechtigungstyp', $this->text_domain); ?></th>
			<th><?php esc_html_e('Öffentlich', $this->text_domain); ?></th>
			<th><?php esc_html_e('Hierarchisch', $this->text_domain); ?></th>
			<th><?php esc_html_e('Umschreiben', $this->text_domain); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th><?php esc_html_e('Beitragstyp', $this->text_domain); ?></th>
			<th><?php esc_html_e('Name', $this->text_domain); ?></th>
			<th><?php esc_html_e('Beschreibung', $this->text_domain); ?></th>
			<th><?php esc_html_e('Menü Icon', $this->text_domain); ?></th>
			<th><?php esc_html_e('Unterstützt', $this->text_domain); ?></th>
			<th><?php esc_html_e('Berechtigungstyp', $this->text_domain); ?></th>
			<th><?php esc_html_e('Öffentlich', $this->text_domain); ?></th>
			<th><?php esc_html_e('Hierarchisch', $this->text_domain); ?></th>
			<th><?php esc_html_e('Umschreiben', $this->text_domain); ?></th>
		</tr>
	</tfoot>
	<tbody>
		<?php
		$i = 0;
		foreach ( $post_types as $source => $pt ):
		$flag = ($source == 'net') && ! is_network_admin();
		foreach ( $pt as $name => $post_type ):
		$class = ( $i % 2) ? 'ct-edit-row alternate' : 'ct-edit-row'; $i++;
		?>
		<tr class="<?php echo $class; ?>">
			<td style="min-width:8em;">
				<strong>
					<?php
					if($flag):
					echo $name;
					else:
					?>
					<a href="<?php echo esc_url( self_admin_url( 'admin.php?page=' . $_GET['page'] . '&amp;ct_content_type=post_type&amp;ct_edit_post_type=' . $name ) ); ?>"><?php echo esc_html( $name ); ?></a>
					<?php endif; ?>
				</strong>
				<div class="row-actions" id="row-actions-<?php echo $name; ?>">
					<?php if($flag): ?>
					<span class="description"><?php esc_html_e('In Netzwerkadministration bearbeiten.', $this->text_domain); ?></span>
					<?php else: ?>
					<span class="edit">
						<a title="<?php esc_attr_e('Bearbeiten Sie den Beitragstyp', $this->text_domain); ?>" href="<?php echo esc_url(self_admin_url( 'admin.php?page=' . $_GET['page'] . '&amp;ct_content_type=post_type&amp;ct_edit_post_type=' . $name ) ); ?>"><?php esc_html_e('Bearbeiten', $this->text_domain); ?></a> |
					</span>
					<span class="trash">
						<a class="submitdelete" href="#" onclick="javascript:content_types.toggle_delete('<?php echo( $name ); ?>'); return false;"><?php esc_html_e('Löschen', $this->text_domain); ?></a>
					</span>
					<?php endif; ?>
				</div>
				<form action="#" method="post" id="form-<?php echo( $name ); ?>" class="del-form">
					<?php wp_nonce_field('delete_post_type'); ?>
					<input type="hidden" name="post_type_name" value="<?php echo( $name ); ?>" />
					<input type="submit" class="button confirm" value="<?php esc_attr_e( 'Bestätigen', $this->text_domain ); ?>" name="submit" />
					<input type="submit" class="button cancel"  value="<?php esc_attr_e( 'Abbrechen', $this->text_domain ); ?>" onClick="content_types.cancel('<?php echo( $name ); ?>'); return false;" />
				</form>
			</td>
			<td><?php echo ( empty( $post_type['labels']['name'] ) ) ? '' : esc_html( $post_type['labels']['name'] ); ?></td>
			<td><?php echo ( empty( $post_type['description'] ) ) ? '' : esc_html( $post_type['description'] ); ?></td>
			<td>
				<img src="<?php echo esc_html(empty( $post_type['menu_icon'] ) ) ? $this->plugin_url . 'ui-admin/images/default-menu-icon.png' : $post_type['menu_icon']; ?>" alt="<?php if ( empty( $post_type['menu_icon'] ) ) echo( 'Kein Icon'); ?>" />
			</td>
			<td class="ct-supports">
				<?php foreach ( $post_type['supports'] as $value ): ?>
				<?php echo esc_html( $value ); ?>
				<?php endforeach; ?>
			</td>
			<td><?php echo( $post_type['capability_type'] ); ?></td>
			<td class="ct-tf-icons-wrap">
				<?php if ( isset( $post_type['public'] ) && $post_type['public'] === NULL ): ?>
				<img class="ct-tf-icons" src="<?php echo $this->plugin_url . 'ui-admin/images/advanced.png'; ?>" alt="<?php esc_attr_e('Erweitert', $this->text_domain); ?>" title="<?php esc_attr_e('Erweitert', $this->text_domain); ?>" />
				<?php elseif ( isset( $post_type['public'] ) ): ?>
				<img class="ct-tf-icons" src="<?php echo $this->plugin_url . 'ui-admin/images/true.png'; ?>" alt="<?php esc_attr_e('Wahr', $this->text_domain); ?>" title="<?php esc_attr_e('Wahr', $this->text_domain); ?>" />
				<?php else: ?>
				<img class="ct-tf-icons" src="<?php echo $this->plugin_url . 'ui-admin/images/false.png'; ?>" alt="<?php esc_attr_e('Falsch', $this->text_domain); ?>" title="<?php esc_attr_e('Falsch', $this->text_domain); ?>" />
				<?php endif; ?>
			</td>
			<td class="ct-tf-icons-wrap">
				<?php if ( $post_type['hierarchical'] ): ?>
				<img class="ct-tf-icons" src="<?php echo $this->plugin_url . 'ui-admin/images/true.png'; ?>" alt="<?php esc_attr_e('Wahr', $this->text_domain); ?>" title="<?php esc_attr_e('Wahr', $this->text_domain); ?>" />
				<?php else: ?>
				<img class="ct-tf-icons" src="<?php echo $this->plugin_url . 'ui-admin/images/false.png'; ?>" alt="<?php esc_attr_e('Falsch', $this->text_domain); ?>" title="<?php esc_attr_e('Falsch', $this->text_domain); ?>" />
				<?php endif; ?>
			</td>
			<td class="ct-tf-icons-wrap">
				<?php if ( $post_type['rewrite'] ): ?>
				<img class="ct-tf-icons" src="<?php echo $this->plugin_url . 'ui-admin/images/true.png'; ?>" alt="<?php esc_attr_e('Wahr', $this->text_domain); ?>" title="<?php esc_attr_e('Wahr', $this->text_domain); ?>" />
				<?php else: ?>
				<img class="ct-tf-icons" src="<?php echo $this->plugin_url . 'ui-admin/images/false.png'; ?>" alt="<?php esc_attr_e('Falsch', $this->text_domain); ?>" title="<?php esc_attr_e('Falsch', $this->text_domain); ?>" />
				<?php endif; ?>
			</td>
		</tr>
		<?php
		endforeach;
		endforeach;
		?>
	</tbody>
</table>

<form action="#" method="post" class="ct-form-single-btn">
	<input type="submit" class="button-secondary" name="redirect_add_post_type" value="<?php esc_attr_e('Beitragstyp hinzufügen', $this->text_domain); ?>" />
</form>
