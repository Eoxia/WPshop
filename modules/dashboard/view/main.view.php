<?php
/**
 * La vue principale du tableau de bord.
 *
 * @package   WPshop
 * @author    Eoxia <technique@eoxia.com>
 * @copyright (c) 2011-2022 Eoxia <technique@eoxia.com>.
 * @since     2.0.0
 * @version   2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit; ?>

<style>
	.wps-dashboard-card {
		background: #fff;
		border-radius: 8px;
		box-shadow: 0 4px 15px rgba(0,0,0,0.05);
		margin-bottom: 20px;
		overflow: hidden;
		font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
	}
	.wps-dashboard-card-header {
		padding: 20px;
		background: #fff;
	}
	.wps-dashboard-card-title {
		margin: 0;
		font-size: 18px;
		font-weight: 700;
		color: #111;
	}
	.wps-dashboard-card-title a {
		font-size: 12px;
		font-weight: normal;
		float: right;
		color: #1897e7;
		text-decoration: none;
		margin-top: 4px;
	}
	.wps-dashboard-table {
		width: 100%;
		border-collapse: collapse;
	}
	.wps-dashboard-table th {
		background: #f5f5f5;
		color: #666;
		font-weight: 500;
		font-size: 13px;
		text-align: left;
		padding: 12px 20px;
		border-bottom: 1px solid #eaeaea;
	}
	.wps-dashboard-table td {
		padding: 15px 20px;
		font-size: 14px;
		color: #222;
		border-bottom: 1px solid #f0f0f0;
		vertical-align: middle;
	}
	.wps-dashboard-table tr:last-child td {
		border-bottom: none;
	}
	.wps-dashboard-table td a {
		color: #222;
		text-decoration: none;
	}
	.wps-dashboard-table td a:hover {
		color: #1897e7;
	}
	.wps-status-dot {
		display: inline-block;
		width: 16px;
		height: 16px;
		border-radius: 50%;
	}
	.wps-status-dot.filled-gold { background: #d4af37; border: 1px solid #c5a028; }
	.wps-status-dot.empty-gold { background: transparent; border: 1px solid #d4af37; }
	.wps-icon-customer {
		color: #6a5acd;
		margin-right: 8px;
		font-size: 16px;
		vertical-align: middle;
	}
	.wps-icon-warning {
		color: #d4af37;
		margin-left: 8px;
		font-size: 14px;
	}
	.wps-dashboard-total-row {
		border-top: 1px solid #eaeaea;
		background: #fdfdfd;
	}
	.wps-dashboard-total-row td {
		padding: 20px;
		font-weight: 600;
		font-size: 15px;
	}
</style>

<div class="wrap wpeo-wrap">
	<div class="wpeo-gridlayout grid-2" style="gap: 20px;">
		<?php do_action( 'wps_dashboard' ); ?>
	</div>
</div>
