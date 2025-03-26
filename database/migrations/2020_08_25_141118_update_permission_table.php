<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permission', function (Blueprint $table) {
            $table->text('routes')->nullable()->after('description')->change();
            $table->text('methods')->nullable()->after('routes')->change();
        });

        \App\Models\Permission::truncate();
        \App\Models\Rolepermission::truncate();

        \App\Models\Permission::insert([
            ['name' => 'View Customer','module' => 'customer','description' => 'User can view customer list and detail','routes' => '["customers.index","customers.show"]','methods' => '["index","show"]','user_id' => '1','is_view_page' => '1'],
            ['name' => 'Create Customer','module' => 'customer','description' => 'User can create customers','routes' => '["customers.create","customers.store"]','methods' => '["create","store"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Edit Customer','module' => 'customer','description' => 'User can edit customers','routes' => '["customers.edit","customers.update"]','methods' => '["edit","update"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Change Status of Customer','module' => 'customer','description' => 'User can change status of customers','routes' => '["customer.deactivate","customer.activate"]','methods' => '["deactivate","activate"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'View Job Order','module' => 'job_order','description' => 'User can view job order list and detail','routes' => '["joborders.index","joborders.show"]','methods' => '["index","show"]','user_id' => '1','is_view_page' => '1'],
            ['name' => 'Create Job Order','module' => 'job_order','description' => 'User can create job orders','routes' => '["joborders.create","joborders.store"]','methods' => '["create","store"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Create Pantaga or Display','module' => 'job_order','description' => 'User can create pantaga or display','routes' => '["joborders.create-pantaga-or-display","joborders.create-pantaga-or-display","joborders.pantage-or-display-store"]','methods' => '["create_pantaga_or_display","store_pantaga_or_display"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Delete Job Order or Pantaga','module' => 'job_order','description' => 'User can delete job orders and pataga','routes' => '["joborders.destroy","jo.delete"]','methods' => '["destroy","delete"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'View Forecaster','module' => 'forecaster','description' => 'User can view forecaster list and detail','routes' => '["forecaster.index"]','methods' => '["index"]','user_id' => '1','is_view_page' => '1'],
            ['name' => 'Assign and Cancel forecast','module' => 'forecaster','description' => 'User can assign and cancel forecast','routes' => '["joborder.assign","forecaster.cancel-order","forecaster.order.multiple.delete"]','methods' => '["assign","cancel"]','user_id' => '1','is_view_page' => '0'],

            ['name' => 'View Daily Leftover','module' => 'left_over','description' => 'User can view daily leftover list and detail','routes' => '["leftover.index","leftover.show","leftover.print"]','methods' => '["index","show","print"]','user_id' => '1','is_view_page' => '1'],
            ['name' => 'Add Daily Leftover','module' => 'left_over','description' => 'User can add daily leftover','routes' => '["leftover.create","leftover.store"]','methods' => '["create","store"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Edit Daily Leftover','module' => 'left_over','description' => 'User can edit daily leftover','routes' => '["leftover.edit_all","leftover.update_all"]','methods' => '["edit_all","update_all"]','user_id' => '1','is_view_page' => '0'],

            ['name' => 'View Sales Summary Report','module' => 'reports','description' => 'User can view sales summary report','routes' => '["admin.report.sales"]','methods' => '["sales"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'View Sales Payment Report','module' => 'reports','description' => 'User can view sales payment report','routes' => '["admin.report.sales_payment"]','methods' => '["sales_payment"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'View Delivery Status Report','module' => 'reports','description' => 'User can view delivery status report','routes' => '["admin.report.delivery_status"]','methods' => '["delivery_status"]','user_id' => '1','is_view_page' => '0'],
//            ['name' => 'View Branch Sales Summary','module' => 'reports','description' => 'User can view branch sales summary','routes' => '["admin.report.sales"]','methods' => '["sales"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'View Job Order Report','module' => 'reports','description' => 'User can view job order report','routes' => '["admin.report.joborder"]','methods' => '["joborder"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'View Leftover Report','module' => 'reports','description' => 'User can view leftover report','routes' => '["admin.report.leftover"]','methods' => '["leftover"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'View Sales Per Agent Report','module' => 'reports','description' => 'User can view sales per agent report','routes' => '["admin.report.sales-per-agent"]','methods' => '["sales_per_agent"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'View Sales per Customer Report','module' => 'reports','description' => 'User can view sales per customer report','routes' => '["admin.report.sales-per-customer"]','methods' => '["sales_per_customer"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'View Forecaster Report','module' => 'reports','description' => 'User can view forecaster report','routes' => '["admin.report.forecaster"]','methods' => '["forecaster"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'View Delivery Report','module' => 'reports','description' => 'User can view delivery report','routes' => '["admin.report.door2door_report"]','methods' => '["door2door_report"]','user_id' => '1','is_view_page' => '0'],

            ['name' => 'View Page','module' => 'page','description' => 'User can view page list and detail','routes' => '["pages.index","pages.show","pages.index.advance-search"]','methods' => '["index","show","advance_index"]','user_id' => '1','is_view_page' => '1'],
            ['name' => 'Create Page','module' => 'page','description' => 'User can create pages','routes' => '["pages.create","pages.store"]','methods' => '["create","store"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Edit Page','module' => 'page','description' => 'User can edit pages','routes' => '["pages.edit","pages.update"]','methods' => '["edit","update"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Delete/Restore page','module' => 'page','description' => 'User can delete and restore pages','routes' => '["pages.destroy","pages.delete","pages.restore"]','methods' => '["destroy","delete","restore"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Change Status of Page','module' => 'page','description' => 'User can change status of pages','routes' => '["pages.change.status"]','methods' => '["change_status"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'View Album','module' => 'banner','description' => 'User can view album list and detail','routes' => '["albums.index","albums.show"]','methods' => '["index","show"]','user_id' => '1','is_view_page' => '1'],
            ['name' => 'Create Album','module' => 'banner','description' => 'User can create albums','routes' => '["albums.create","albums.store"]','methods' => '["create","store"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Edit Album','module' => 'banner','description' => 'User can edit albums','routes' => '["albums.edit","albums.update","albums.quick_update"]','methods' => '["edit","update","quick_update"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Delete/Restore album','module' => 'banner','description' => 'User can delete and restore albums','routes' => '["albums.destroy","albums.destroy_many","albums.restore"]','methods' => '["destroy","destroy_many","restore"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Manage File manager','module' => 'file_manager','description' => 'User can manage file manager','routes' => '["file-manager.show","file-manager.upload","file-manager.index"]','methods' => '["show","upload","index"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'View menu','module' => 'menu','description' => 'User can view menu list and detail','routes' => '["menus.index","menus.show"]','methods' => '["index","show"]','user_id' => '1','is_view_page' => '1'],
            ['name' => 'Create Menu','module' => 'menu','description' => 'User can create menus','routes' => '["menus.create","menus.store"]','methods' => '["create","store"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Edit Menu','module' => 'menu','description' => 'User can edit menus','routes' => '["menus.edit","menus.update"]','methods' => '["edit","update"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Delete/Restore menu','module' => 'menu','description' => 'User can delete and restore menus','routes' => '["menus.destroy","menus.destroy_many","menus.restore"]','methods' => '["destroy","destroy_many","restore"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'View news','module' => 'news','description' => 'User can view news list and detail','routes' => '["news.index","news.show","news.index.advance-search"]','methods' => '["index","show","advance_index"]','user_id' => '1','is_view_page' => '1'],
            ['name' => 'Create News','module' => 'news','description' => 'User can create news','routes' => '["news.create","news.store"]','methods' => '["create","store"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Edit news','module' => 'news','description' => 'User can edit news','routes' => '["news.edit","news.update"]','methods' => '["edit","update"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Delete/Restore News','module' => 'news','description' => 'User can delete and restore news','routes' => '["news.destroy","news.delete","news.restore"]','methods' => '["destroy","delete","restore"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Change Status of News','module' => 'news','description' => 'User can change status of news','routes' => '["news.change.status"]','methods' => '["change_status"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'View News Category','module' => 'news_category','description' => 'User can view news category list and details','routes' => '["news-categories.index","news-categories.show"]','methods' => '["index","show"]','user_id' => '1','is_view_page' => '1'],
            ['name' => 'Create news category','module' => 'news_category','description' => 'User can create news categories','routes' => '["news-categories.create","news-categories.store"]','methods' => '["create","store"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Edit news category','module' => 'news_category','description' => 'User can edit news categories','routes' => '["news-categories.edit","news-categories.update"]','methods' => '["edit","update"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Delete/Restore news category','module' => 'news_category','description' => 'User can delete and restore news categories','routes' => '["news-categories.destroy","news-categories.delete","news-categories.restore"]','methods' => '["destroy","delete","restore"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Edit website settings','module' => 'website_settings','description' => 'User can edit website settings','routes' => '["website-settings.edit","website-settings.update","website-settings.update-contacts","website-settings.update-media-accounts","website-settings.update-data-privacy","website-settings.remove-logo","website-settings.remove-icon","website-settings.remove-media"]','methods' => '["edit","update","update_contacts","update_media_accounts","update_data_privacy","remove_logo","remove_icon","remove_media"]','user_id' => '1','is_view_page' => '1'],
            ['name' => 'View audit logs','module' => 'audit_logs','description' => 'User can view audit logs','routes' => '["settings.audit"]','methods' => '["index"]','user_id' => '1','is_view_page' => '1'],
            ['name' => 'View users','module' => 'user','description' => 'User can view user list and detail','routes' => '["users.index","users.show","user.search","user.activity.search"]','methods' => '["index","show","search","filter"]','user_id' => '1','is_view_page' => '1'],
            ['name' => 'Create user','module' => 'user','description' => 'User can create users','routes' => '["users.create","users.store"]','methods' => '["create","store"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Edit user','module' => 'user','description' => 'User can edit users','routes' => '["users.edit","users.update"]','methods' => '["edit","update"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Change status of user','module' => 'user','description' => 'User can change status of users','routes' => '["user.deactivate","user.activate"]','methods' => '["deactivate","activate"]','user_id' => '1','is_view_page' => '0'],

            ['name' => 'View Subscriber','module' => 'subscriber','description' => 'User can view subscriber list and detail','routes' => '["mailing-list.subscribers.index","mailing-list.subscribers.show","mailing-list.subscribers.index.unsubscribe"]','methods' => '["index","show","unsubscribe"]','user_id' => '1','is_view_page' => '1'],
            ['name' => 'Create Subscriber','module' => 'subscriber','description' => 'User can create subscribers','routes' => '["mailing-list.subscribers.create","mailing-list.subscribers.store"]','methods' => '["create","store"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Edit Subscriber','module' => 'subscriber','description' => 'User can edit subscribers','routes' => '["mailing-list.subscribers.edit","mailing-list.subscribers.update"]','methods' => '["edit","update"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Change Status of Subscriber','module' => 'subscriber','description' => 'User can change status of subscribers','routes' => '["mailing-list.subscribers.change-status"]','methods' => '["change_status"]','user_id' => '1','is_view_page' => '0'],

            ['name' => 'View Subscriber Group','module' => 'subscriber_group','description' => 'User can view subscriber group list and detail','routes' => '["mailing-list.groups.index","mailing-list.groups.show"]','methods' => '["index","show"]','user_id' => '1','is_view_page' => '1'],
            ['name' => 'Create Subscriber Group','module' => 'subscriber_group','description' => 'User can create subscriber group','routes' => '["mailing-list.groups.create","mailing-list.groups.store"]','methods' => '["create","store"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Edit Subscriber Group','module' => 'subscriber_group','description' => 'User can edit subscriber group','routes' => '["mailing-list.groups.edit","mailing-list.groups.update"]','methods' => '["edit","update"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Delete/Restore Subscriber Group','module' => 'subscriber_group','description' => 'User can delete and restore subscriber group','routes' => '["mailing-list.groups.destroy","mailing-list.groups.destroy_many","mailing-list.groups.restore"]','methods' => '["destroy","delete","restore"]','user_id' => '1','is_view_page' => '0'],

            ['name' => 'View Campaign','module' => 'campaign','description' => 'User can view campaign list and detail','routes' => '["mailing-list.campaigns.index","mailing-list.campaigns.show"]','methods' => '["index","show"]','user_id' => '1','is_view_page' => '1'],
            ['name' => 'Create Campaign','module' => 'campaign','description' => 'User can create campaigns','routes' => '["mailing-list.campaigns.create","mailing-list.campaigns.store"]','methods' => '["create","store"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Edit Campaign','module' => 'campaign','description' => 'User can edit campaigns','routes' => '["mailing-list.campaigns.edit","mailing-list.campaigns.update"]','methods' => '["edit","update"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Delete/Restore Campaign','module' => 'campaign','description' => 'User can delete and restore campaigns','routes' => '["mailing-list.campaigns.destroy","mailing-list.campaigns.destroy_many","mailing-list.campaigns.restore"]','methods' => '["destroy","delete","restore"]','user_id' => '1','is_view_page' => '0'],


            ['name' => 'View Product','module' => 'product','description' => 'User can view product list and detail','routes' => '["products.index","products.show"]','methods' => '["index","show"]','user_id' => '1','is_view_page' => '1'],
            ['name' => 'Create Product','module' => 'product','description' => 'User can create products','routes' => '["products.create","products.store"]','methods' => '["create","store"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Edit Product','module' => 'product','description' => 'User can edit products','routes' => '["products.edit","products.update"]','methods' => '["edit","update"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Delete Product','module' => 'product','description' => 'User can delete products','routes' => '["products.destroy","products.multiple.delete"]','methods' => '["destroy","multiple_delete"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Change Status of Product','module' => 'product','description' => 'User can change status of products','routes' => '["product.single-change-status","product.multiple.change.status"]','methods' => '["change_status","multiple_change_status"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'View Product Category','module' => 'product_category','description' => 'User can view product category list and details','routes' => '["product-categories.index","product-categories.show"]','methods' => '["index","show"]','user_id' => '1','is_view_page' => '1'],
            ['name' => 'Create Product Category','module' => 'product_category','description' => 'User can create product categories','routes' => '["product-categories.create","product-categories.store"]','methods' => '["create","store"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Edit Product Category','module' => 'product_category','description' => 'User can edit product categories','routes' => '["product-categories.edit","product-categories.update"]','methods' => '["edit","update"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Delete Product Category','module' => 'product_category','description' => 'User can delete product categories','routes' => '["product-categories.destroy","product.category.single.delete"]','methods' => '["destroy","single_delete"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Change Status of Product Category','module' => 'product_category','description' => 'User can change status of product categories','routes' => '["product.category.change-status","product.category.multiple.change.status"]','methods' => '["update_status","multiple_change_status"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'View Production Branch','module' => 'production_branch','description' => 'User can view production branch list and detail','routes' => '["production-branches.index","production-branches.show"]','methods' => '["index","show"]','user_id' => '1','is_view_page' => '1'],
            ['name' => 'Create Production Branch','module' => 'production_branch','description' => 'User can create production branch','routes' => '["production-branches.create","production-branches.store"]','methods' => '["create","store"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Edit Production Branch','module' => 'production_branch','description' => 'User can edit production branch','routes' => '["production-branches.edit","production-branches.update"]','methods' => '["edit","update"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Delete Production Branch','module' => 'production_branch','description' => 'User can delete production braches','routes' => '["production-branches.single.delete","production-branches.multiple.delete"]','methods' => '["single_delete","multiple_delete"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'View Gift Certificate','module' => 'gift_certificate','description' => 'User can view gift certificate list and detail','routes' => '["gift-certificate.index","gift-certificate.show"]','methods' => '["index","show"]','user_id' => '1','is_view_page' => '1'],
            ['name' => 'Create Gift Certificate','module' => 'gift_certificate','description' => 'User can create gift certificate','routes' => '["gift-certificate.create","gift-certificate.store"]','methods' => '["create","store"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Edit Gift Certificate','module' => 'gift_certificate','description' => 'User can edit gift certificate','routes' => '["gift-certificate.edit","gift-certificate.update"]','methods' => '["edit","update"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Delete/Restore Gift Certificate','module' => 'gift_certificate','description' => 'User can delete and restore gift certificate','routes' => '["gift-certificate.restore","gift-certificate.single.delete","gift-certificate.multiple.delete"]','methods' => '["restore","single_delete","multiple_delete"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Change Status of Gift Certificate','module' => 'gift_certificate','description' => 'User can change status of gift certificate','routes' => '["gift-certificate.change.status"]','methods' => '["change_status"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'View Delivery Rate','module' => 'delivery_rate','description' => 'User can view delivery rate list and detail','routes' => '["admin.locations.index","admin.locations.show"]','methods' => '["index","show"]','user_id' => '1','is_view_page' => '1'],
            ['name' => 'Create Delivery Rate','module' => 'delivery_rate','description' => 'User can create delivery rates','routes' => '["admin.locations.create","admin.locations.store"]','methods' => '["create","store"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Edit Delivery Rate','module' => 'delivery_rate','description' => 'User can edit delivery rates','routes' => '["admin.locations.edit","admin.locations.update"]','methods' => '["edit","update"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Delete Delivery Rate','module' => 'delivery_rate','description' => 'User can delete delivery rates','routes' => '["admin.locations.destroy","admin.location.delete"]','methods' => '["destroy","delete"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'View Branch','module' => 'branch','description' => 'User can view branch list and detail','routes' => '["branch.index","branch.show"]','methods' => '["index","show"]','user_id' => '1','is_view_page' => '1'],
            ['name' => 'Create Branch','module' => 'branch','description' => 'User can create branches','routes' => '["branch.create","branch.store"]','methods' => '["create","store"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Edit Branch','module' => 'branch','description' => 'User can edit branches','routes' => '["branch.edit","branch.update"]','methods' => '["edit","update"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Delete/Restore Branch','module' => 'branch','description' => 'User can delete and restore branches','routes' => '["branch.restore","branch.single.delete","branch.multiple.delete"]','methods' => '["restore","single_delete","multiple_delete"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'View Sales Transaction','module' => 'sales_transaction','description' => 'User can view sales transaction list and detail','routes' => '["sales-transaction.index","sales-transaction.show"]','methods' => '["index","show"]','user_id' => '1','is_view_page' => '1'],
            ['name' => 'Delete/Restore Sales Transaction','module' => 'sales_transaction','description' => 'User can add or delete sales transaction','routes' => '["sales-transaction.destroy","sales-transaction.restore"]','methods' => '["destroy","restore"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Change Status of Sales Transaction','module' => 'sales_transaction','description' => 'User can change status of sales transaction','routes' => '["sales-transaction.quick_update"]','methods' => '["quick_update"]','user_id' => '1','is_view_page' => '0']
        ]);

        \App\Models\Rolepermission::insert([
            ['role_id' => '2','permission_id' => '1','user_id' => '1','isAllowed' => '1'],
            ['role_id' => '4','permission_id' => '1','user_id' => '1','isAllowed' => '1'],
            ['role_id' => '2','permission_id' => '2','user_id' => '1','isAllowed' => '1'],
            ['role_id' => '4','permission_id' => '2','user_id' => '1','isAllowed' => '1'],
            ['role_id' => '2','permission_id' => '3','user_id' => '1','isAllowed' => '1'],
            ['role_id' => '4','permission_id' => '3','user_id' => '1','isAllowed' => '1'],
            ['role_id' => '2','permission_id' => '4','user_id' => '1','isAllowed' => '1'],
            ['role_id' => '4','permission_id' => '4','user_id' => '1','isAllowed' => '1'],
            ['role_id' => '2','permission_id' => '5','user_id' => '1','isAllowed' => '1'],
            ['role_id' => '3','permission_id' => '5','user_id' => '1','isAllowed' => '1'],
            ['role_id' => '2','permission_id' => '6','user_id' => '1','isAllowed' => '1'],
            ['role_id' => '3','permission_id' => '6','user_id' => '1','isAllowed' => '1'],
            ['role_id' => '2','permission_id' => '7','user_id' => '1','isAllowed' => '1'],
            ['role_id' => '3','permission_id' => '7','user_id' => '1','isAllowed' => '1'],
            ['role_id' => '2','permission_id' => '8','user_id' => '1','isAllowed' => '1'],
            ['role_id' => '3','permission_id' => '8','user_id' => '1','isAllowed' => '1'],
            ['role_id' => '3','permission_id' => '9','user_id' => '1','isAllowed' => '1'],
            ['role_id' => '3','permission_id' => '10','user_id' => '1','isAllowed' => '1'],
            ['role_id' => '2','permission_id' => '11','user_id' => '1','isAllowed' => '1'],
            ['role_id' => '2','permission_id' => '13','user_id' => '1','isAllowed' => '1'],
            ['role_id' => '3','permission_id' => '13','user_id' => '1','isAllowed' => '1'],
            ['role_id' => '2','permission_id' => '14','user_id' => '1','isAllowed' => '1'],
            ['role_id' => '2','permission_id' => '15','user_id' => '1','isAllowed' => '1'],
            ['role_id' => '3','permission_id' => '15','user_id' => '1','isAllowed' => '1'],
            ['role_id' => '5','permission_id' => '15','user_id' => '1','isAllowed' => '1'],
            ['role_id' => '2','permission_id' => '16','user_id' => '1','isAllowed' => '1'],
            ['role_id' => '4','permission_id' => '16','user_id' => '1','isAllowed' => '1'],
            ['role_id' => '2','permission_id' => '19','user_id' => '1','isAllowed' => '1'],
            ['role_id' => '3','permission_id' => '19','user_id' => '1','isAllowed' => '1'],
            ['role_id' => '5','permission_id' => '19','user_id' => '1','isAllowed' => '1'],
            ['role_id' => '3','permission_id' => '20','user_id' => '1','isAllowed' => '1'],
            ['role_id' => '2','permission_id' => '45','user_id' => '1','isAllowed' => '1'],
            ['role_id' => '2','permission_id' => '58','user_id' => '1','isAllowed' => '1'],
            ['role_id' => '2','permission_id' => '89','user_id' => '1','isAllowed' => '1'],
            ['role_id' => '4','permission_id' => '89','user_id' => '1','isAllowed' => '1'],
            ['role_id' => '2','permission_id' => '90','user_id' => '1','isAllowed' => '1'],
            ['role_id' => '4','permission_id' => '90','user_id' => '1','isAllowed' => '1'],
            ['role_id' => '2','permission_id' => '91','user_id' => '1','isAllowed' => '1'],
            ['role_id' => '4','permission_id' => '91','user_id' => '1','isAllowed' => '1'],
            ['role_id' => '2','permission_id' => '92','user_id' => '1','isAllowed' => '1'],
            ['role_id' => '2','permission_id' => '93','user_id' => '1','isAllowed' => '1'],
            ['role_id' => '2','permission_id' => '94','user_id' => '1','isAllowed' => '1']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permission', function (Blueprint $table) {
            $table->dropColumn('routes');
            $table->dropColumn('methods');
        });
    }
}
