<?php
/**
 * @copyright Incsub (http://incsub.com/)
 *
 * @license http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2 (GPL-2.0)
 * 
 * This program is free software; you can redistribute it and/or modify 
 * it under the terms of the GNU General Public License, version 2, as  
 * published by the Free Software Foundation.                           
 *
 * This program is distributed in the hope that it will be useful,      
 * but WITHOUT ANY WARRANTY; without even the implied warranty of       
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        
 * GNU General Public License for more details.                         
 *
 * You should have received a copy of the GNU General Public License    
 * along with this program; if not, write to the Free Software          
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston,               
 * MA 02110-1301 USA                                                    
 *
*/

/**
 * Membership List Table 
 *
 *
 * @since 4.0.0
 *
 */
class MS_Helper_List_Table_Gateway extends MS_Helper_List_Table {
		
	protected $id = 'gateway';
	
	public function __construct(){
		parent::__construct( array(
				'singular'  => 'gateway',
				'plural'    => 'gateways',
				'ajax'      => false
		) );
	}
	
	public function get_columns() {
		return apply_filters( 'membership_helper_list_table_gateway_columns', array(
			'cb'     => '<input type="checkbox" />',
			'name' => __('Gateway Name', MS_TEXT_DOMAIN ),
			'active' => __('Active', MS_TEXT_DOMAIN ),
		) );
	}
	
	function column_cb( $item ) {
		return sprintf( '<input type="checkbox" name="gateway_id[]" value="%1$s" />', $item->id );
	}
	
	public function get_hidden_columns() {
		return apply_filters( 'gateway_helper_list_table_gateway_hidden_columns', array() );
	}
	
	public function get_sortable_columns() {
		return apply_filters( 'gateway_helper_list_table_gateway_sortable_columns', array() );
	}
	
	public function prepare_items() {
	
		$this->_column_headers = array( $this->get_columns(), $this->get_hidden_columns(), $this->get_sortable_columns() );
		
		$this->items = apply_filters( 'gateway_helper_list_table_gateway_items', MS_Model_Gateway::get_gateways() );
	}

	public function column_name( $item ) {
		$html = sprintf( '<div>%1$s</div><div>%2$s</div>', $item->name, $item->description );
		$actions = array(
				sprintf( '<a href="?page=%s&gateway_id=%s">%s</a>',
						$_REQUEST['page'],
						$item->id,
						__('Settings', MS_TEXT_DOMAIN )
				),
				sprintf( '<a href="?page=%s&gateway_id=%s">%s</a>',
						$_REQUEST['page'],
						$item->id,
						__('View Transactions', MS_TEXT_DOMAIN )
				),
				sprintf( '<a href="%s">%s</a>',
						wp_nonce_url(
							sprintf( '?page=%s&tab=%s&gateway_id=%s&action=%s',
								$_REQUEST['page'],
								$_REQUEST['tab'],
								$item->id,
								'toggle_activation'
							),
							'toggle_activation'
						),
						__('Toggle Activation', MS_TEXT_DOMAIN )
				),
				
		);
		$actions = apply_filters( "gateway_helper_list_table_{$this->id}_column_name_actions", $actions, $item );
		return sprintf( '%1$s %2$s', $html, $this->row_actions( $actions ) );
	}
	
	public function column_default( $item, $column_name ) {
		$html = '';
		switch( $column_name ) {
			case 'active':
				$html = ( $item->active ) ? __( 'Active', MS_TEXT_DOMAIN ) : __( 'Deactivated', MS_TEXT_DOMAIN );
				break;
			default:
				$html = print_r( $item, true ) ;
				break;
		}
		return $html;
	}
	
	public function get_bulk_actions() {
		return apply_filters( 'gateway_helper_list_table_gateway_bulk_actions', array(
			'toggle_activation' => __( 'Toggle Activation', MS_TEXT_DOMAIN ),
		) );
	}
	
}
