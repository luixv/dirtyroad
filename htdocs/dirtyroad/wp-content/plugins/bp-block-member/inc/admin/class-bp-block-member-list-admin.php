<?php

if ( !defined( 'ABSPATH' ) ) exit;


class BP_Block_Member_List_Table extends WP_List_Table {

	private $block_unblock_message = ''; 	// success / error message for action;

	 function __construct() {
		//global $status, $page;
		 parent::__construct( array(
		'singular'=> 'block',
		'plural' => 'blocks',
		'ajax'	=> false //We won't support Ajax for this table
		) );
	 }

	function get_columns() {
		return $columns= array(
			'cb'				=> '<input type="checkbox" />',
			'username'			=> __('User', 'bp-member-notes'),
			'target'			=> __('is Blocking', 'bp-member-notes'),
			'unblock_target'	=> __('UnBlock', 'bp-member-notes')
		);
	}

	public function get_sortable_columns() {
		return $sortable = array(
			'username'=>array('b.user_login', 'ASC'),
		);
	}

	function get_bulk_actions() {
		$actions = array(
			'delete' => 'UnBlock'
		);
		return $actions;
	}


	function delete_block( $id ) {
		global $wpdb;

		$wpdb->query( $wpdb->prepare(
				"DELETE FROM {$wpdb->base_prefix}bp_block_member WHERE id = %d",
				$id
			) );

		$this->block_unblock_message .=
			"<div class='updated below-h2' style='color: green'> Member block was deleted.</div>";

		echo $this->block_unblock_message;
	}

	function process_bulk_action() {

		if ( 'delete'===$this->current_action() ) {
			foreach( $_POST['id'] as $id ) {
				$this->delete_block( $id );
			}
		}

		if ( 'delete-single'===$this->current_action() ) {
			$nonce = $_REQUEST['_wpnonce'];
			if (! wp_verify_nonce($nonce, 'block-nonce') ) die('Security check');

			$this->delete_block( $_GET['id'] );
		}

	}


	function prepare_items() {
		global $wpdb, $_wp_column_headers;
		$screen = get_current_screen();

		$this->process_bulk_action();

		$query = "
			SELECT a.id, a.user_id, a.target_id, b.user_login AS userName, c.user_login AS targetName
			FROM {$wpdb->base_prefix}bp_block_member a
			JOIN {$wpdb->base_prefix}users b ON ( b.ID = a.user_id )
			JOIN {$wpdb->base_prefix}users c ON ( c.ID = a.target_id )
			";

		$orderby = !empty($_GET["orderby"]) ? esc_sql($_GET["orderby"]) : 'ASC';
		$order = !empty($_GET["order"]) ? esc_sql($_GET["order"]) : '';
		if (!empty($orderby) & !empty($order)){ $query.=' ORDER BY '.$orderby.' '.$order; }
		else
			$query.=' ORDER BY b.user_login ASC ';

		$totalitems = $wpdb->query($query); //return the total number of affected rows

		$perpage = 10;

		$paged = !empty($_GET["paged"]) ? esc_sql($_GET["paged"]) : '';

		if (empty($paged) || !is_numeric($paged) || $paged<=0 ){ $paged=1; }

		$totalpages = ceil($totalitems/$perpage);

		if (!empty($paged) && !empty($perpage)){
			$offset=($paged-1)*$perpage;
			$query.=' LIMIT '.(int)$offset.','.(int)$perpage;
		}

		$this->set_pagination_args( array(
			"total_items" => $totalitems,
			"total_pages" => $totalpages,
			"per_page" => $perpage,
		) );

		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);

		$this->items = $wpdb->get_results($query);

	}

	function display_rows() {

		$records = $this->items;

		list( $columns, $hidden ) = $this->get_column_info();

		$alt_color = true;

		if ( !empty($records) ) {
			foreach( $records as $rec ) {

				if ( $alt_color )
					$tr_class = "alt-color1";
				else
					$tr_class = "alt-color2";

				echo '<tr class="' . $tr_class . '" id="record_'.$rec->user_id.'">';

				$alt_color = !$alt_color;

				$block_name_link = bp_core_get_user_domain( $rec->user_id );
				$block_target_link = bp_core_get_user_domain( $rec->target_id );

				foreach ( $columns as $column_name => $column_display_name ) {

					$class = "class='$column_name column-$column_name'";
					$style = "";
					if ( in_array( $column_name, $hidden ) ) $style = ' style="display:none;"';

					$attributes = $class . $style;

					switch ( $column_name ) {

						case "cb":
							echo '<th scope="row" class="check-column">';
							echo '<input type="checkbox" name="id[]" value="' . $rec->id . '"/>';
							echo '</th>';
							break;

						case "username":
							$avatar = bp_core_fetch_avatar ( array( 'item_id' => $rec->user_id, 'type' => 'thumb' ) );
							echo '<td '. $attributes . '>' . $avatar  . '<strong><a href="' . $block_name_link .
								'" title="' . __('Profile', 'bp-block-member') .'" target="_blank">' .
								stripslashes($rec->userName).'</a></strong></td>';
							break;

						case "target":
							$avatar = bp_core_fetch_avatar ( array( 'item_id' => $rec->target_id, 'type' => 'thumb' ) );
							echo '<td '. 'class="username column-username"' . '>'. $avatar  . '<strong><a href="' . $block_target_link .
								'" title="' . __('Profile', 'bp-block-member') .'" target="_blank">' .
								stripslashes($rec->targetName).'</a></strong></td>';
							break;

						case "unblock_target":
							$block_nonce= wp_create_nonce('block-nonce');
							echo '<td '. $attributes . '>';
							echo sprintf('<a href="?page=%s&action=%s&id=%s&_wpnonce=%s">' .
								__('UnBlock', 'bp-block-member') .
								'</a>',$_REQUEST['page'],'delete-single',$rec->id,$block_nonce);
							echo '</td>';
							break;

					}
				}
				echo'</tr>';
			}
		}
	}
}  // end of BP_Block_Member_List_Table class
