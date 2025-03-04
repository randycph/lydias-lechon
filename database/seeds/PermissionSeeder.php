<?php

use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Permission::insert([
            // Website Settings
            [
                'name' => 'admin/web/edit',
                'module' => 'website settings',
                'description' => 'user can edit website settings',
                'user_id' => 1,
                'is_view_page' => false
            ],
            // Users
            [
                'name' => 'admin/users',
                'module' => 'user',
                'description' => 'user can view users',
                'user_id' => 1,
                'is_view_page' => true
            ],
            [
                'name' => 'admin/user/create',
                'module' => 'user',
                'description' => 'user can create a user',
                'user_id' => 1,
                'is_view_page' => false
            ],
            [
                'name' => 'admin/user/edit',
                'module' => 'user',
                'description' => 'user can edit user',
                'user_id' => 1,
                'is_view_page' => false
            ],
            [
                'name' => 'admin/user/show',
                'module' => 'user',
                'description' => 'user can show user profile',
                'user_id' => 1,
                'is_view_page' => true
            ],
            [
                'name' => 'admin/user/delete',
                'module' => 'user',
                'description' => 'user can delete user',
                'user_id' => 1,
                'is_view_page' => false
            ],
            [
                'name' => 'admin/user/deactivate',
                'module' => 'user',
                'description' => 'user can deactive user',
                'user_id' => 1,
                'is_view_page' => false
            ],
            [
                'name' => 'admin/user/activate',
                'module' => 'user',
                'description' => 'user can activate user',
                'user_id' => 1,
                'is_view_page' => false
            ],
            // Role
            [
                'name' => 'admin/role',
                'module' => 'role',
                'description' => 'user can view role',
                'user_id' => 1,
                'is_view_page' => true
            ],
            [
                'name' => 'admin/role/create',
                'module' => 'role',
                'description' => 'user can create user role',
                'user_id' => 1,
                'is_view_page' => false
            ],
            [
                'name' => 'admin/role/edit',
                'module' => 'role',
                'description' => 'user can edit user role',
                'user_id' => 1,
                'is_view_page' => false
            ],
            [
                'name' => 'admin/role/delete',
                'module' => 'role',
                'description' => 'user can delete user role',
                'user_id' => 1,
                'is_view_page' => false
            ],
            [
                'name' => 'admin/user/restore',
                'module' => 'user',
                'description' => 'user can restore deleted user',
                'user_id' => 1,
                'is_view_page' => false
            ],
            // Permission
            [
                'name' => 'admin/permission',
                'module' => 'permission',
                'description' => 'user can view permission page',
                'user_id' => 1,
                'is_view_page' => true
            ],
            [
                'name' => 'admin/permission/create',
                'module' => 'permission',
                'description' => 'user can create a permission',
                'user_id' => 1,
                'is_view_page' => false
            ],
            [
                'name' => 'admin/permission/edit',
                'module' => 'permission',
                'description' => 'user can edit permission',
                'user_id' => 1,
                'is_view_page' => false
            ],
            [
                'name' => 'admin/permission/delete',
                'module' => 'permission',
                'description' => 'user can delete permission',
                'user_id' => 1,
                'is_view_page' => false
            ],
            // Pages
            [
                'name' => 'admin/page',
                'module' => 'pages',
                'description' => 'user can view pages',
                'user_id' => 1,
                'is_view_page' => true
            ],
            [
                'name' => 'admin/page/create',
                'module' => 'pages',
                'description' => 'user can create a page',
                'user_id' => 1,
                'is_view_page' => false
            ],
            [
                'name' => 'admin/page/edit',
                'module' => 'pages',
                'description' => 'user can edit a page',
                'user_id' => 1,
                'is_view_page' => false
            ],
            [
                'name' => 'admin/page/show',
                'module' => 'pages',
                'description' => 'user can show page details',
                'user_id' => 1,
                'is_view_page' => false
            ],
            [
                'name' => 'admin/page/publish',
                'module' => 'pages',
                'description' => 'user can publish a page',
                'user_id' => 1,
                'is_view_page' => false
            ],
            [
                'name' => 'admin/page/private',
                'module' => 'pages',
                'description' => 'user can update page into private',
                'user_id' => 1,
                'is_view_page' => false
            ],
            [
                'name' => 'admin/page/delete',
                'module' => 'pages',
                'description' => 'user can delete a page',
                'user_id' => 1,
                'is_view_page' => false
            ],
            // News
            [
                'name' => 'admin/news',
                'module' => 'news',
                'description' => 'user can view news',
                'user_id' => 1,
                'is_view_page' => true
            ],
            [
                'name' => 'admin/news/create',
                'module' => 'news',
                'description' => 'user can create a news',
                'user_id' => 1,
                'is_view_page' => false
            ],
            [
                'name' => 'admin/news/edit',
                'module' => 'news',
                'description' => 'user can edit a news',
                'user_id' => 1,
                'is_view_page' => false
            ],
            [
                'name' => 'admin/news/delete',
                'module' => 'news',
                'description' => 'user can delete a news',
                'user_id' => 1,
                'is_view_page' => false
            ],
            [
                'name' => 'admin/news/show',
                'module' => 'news',
                'description' => 'user can show news details',
                'user_id' => 1,
                'is_view_page' => false
            ],
            [
                'name' => 'admin/news/published',
                'module' => 'news',
                'description' => 'user can publish a news',
                'user_id' => 1,
                'is_view_page' => false
            ],
            // Menus
            [
                'name' => 'admin/menu',
                'module' => 'menu',
                'description' => 'user can view menus',
                'user_id' => 1,
                'is_view_page' => true
            ],
            [
                'name' => 'admin/menu/create',
                'module' => 'menu',
                'description' => 'user can create a menu',
                'user_id' => 1,
                'is_view_page' => false
            ],
            [
                'name' => 'admin/menu/edit',
                'module' => 'menu',
                'description' => 'user can edit a menu',
                'user_id' => 1,
                'is_view_page' => false
            ],
            [
                'name' => 'admin/menu/delete',
                'module' => 'menu',
                'description' => 'user can delete a menu',
                'user_id' => 1,
                'is_view_page' => false
            ],
            // Album
            [
                'name' => 'admin/album',
                'module' => 'album',
                'description' => 'user can view albums',
                'user_id' => 1,
                'is_view_page' => false
            ],
            [
                'name' => 'admin/album/create',
                'module' => 'album',
                'description' => 'user can create an album',
                'user_id' => 1,
                'is_view_page' => false
            ],
            [
                'name' => 'admin/album/edit',
                'module' => 'album',
                'description' => 'user can edit an album',
                'user_id' => 1,
                'is_view_page' => false
            ],
            [
                'name' => 'admin/banner/edit',
                'module' => 'album',
                'description' => 'user can edit a banner',
                'user_id' => 1,
                'is_view_page' => false
            ],
            [
                'name' => 'admin/album/delete',
                'module' => 'album',
                'description' => 'user can delete an album',
                'user_id' => 1,
                'is_view_page' => false
            ],
            // CMS Settings
            [
                'name' => 'admin/settings',
                'module' => 'cms settings',
                'description' => 'user can view cms settings',
                'user_id' => 1,
                'is_view_page' => true
            ],
            // Logs
            [
                'name' => 'admin/logs',
                'module' => 'audit logs',
                'description' => 'user can view activity logs',
                'user_id' => 1,
                'is_view_page' => true
            ],
//            // Account Settings
//            [
//                'name' => 'admin/account/edit',
//                'module' => 'account settings',
//                'description' => 'user can edit account settings',
//                'user_id' => 1,
//                'is_view_page' => false
//            ],
            // Category
            [
                'name' => 'admin/category',
                'module' => 'category',
                'description' => 'user can view category',
                'user_id' => 1,
                'is_view_page' => true
            ],
            [
                'name' => 'admin/category/create',
                'module' => 'category',
                'description' => 'user can create a category',
                'user_id' => 1,
                'is_view_page' => false
            ],
            [
                'name' => 'admin/category/edit',
                'module' => 'category',
                'description' => 'user can edit a category',
                'user_id' => 1,
                'is_view_page' => false
            ],
            [
                'name' => 'admin/category/delete',
                'module' => 'category',
                'description' => 'user can delete a category',
                'user_id' => 1,
                'is_view_page' => false
            ],
            [
                'name' => 'admin/category/restore',
                'module' => 'category',
                'description' => 'user can restore a category',
                'user_id' => 1,
                'is_view_page' => false
            ],
            [
                'name' => 'admin/category/restore',
                'module' => 'category',
                'description' => 'user can restore a category',
                'user_id' => 1,
                'is_view_page' => false
            ],
            [
                'name' => 'admin/file-manager',
                'module' => 'file manager',
                'description' => 'user can access a file manager',
                'user_id' => 1,
                'is_view_page' => true
            ],
//            [
//                'name' => 'admin/dashboard',
//                'module' => 'dashboard',
//                'description' => 'user can view dashboard page',
//                'user_id' => 1,
//                'is_view_page' => true
//            ],
            [
                'name' => 'admin/videos',
                'module' => 'videos',
                'description' => 'user can view video page',
                'user_id' => 1,
                'is_view_page' => true
            ],
            [
                'name' => 'admin/video/create',
                'module' => 'videos',
                'description' => 'user can create a video',
                'user_id' => 1,
                'is_view_page' => false
            ],
            [
                'name' => 'admin/video/edit',
                'module' => 'videos',
                'description' => 'user can edit a video',
                'user_id' => 1,
                'is_view_page' => false
            ],
            [
                'name' => 'admin/video/delete',
                'module' => 'videos',
                'description' => 'user can delete a video',
                'user_id' => 1,
                'is_view_page' => false
            ],
            [
                'name' => 'admin/video/restore',
                'module' => 'videos',
                'description' => 'user can restore a video',
                'user_id' => 1,
                'is_view_page' => false
            ],
            [
                'name' => 'admin/video/publish',
                'module' => 'videos',
                'description' => 'user can publish a video',
                'user_id' => 1,
                'is_view_page' => false
            ],
            [
                'name' => 'admin/video-category',
                'module' => 'video category',
                'description' => 'user can view video category page',
                'user_id' => 1,
                'is_view_page' => true
            ],
            [
                'name' => 'admin/video-category/create',
                'module' => 'video category',
                'description' => 'user can create a video category',
                'user_id' => 1,
                'is_view_page' => false
            ],
            [
                'name' => 'admin/video-category/edit',
                'module' => 'video category',
                'description' => 'user can edit a video category',
                'user_id' => 1,
                'is_view_page' => false
            ],
            [
                'name' => 'admin/video-category/delete',
                'module' => 'video category',
                'description' => 'user can delete a video category',
                'user_id' => 1,
                'is_view_page' => false
            ],
            [
                'name' => 'admin/video-category/restore',
                'module' => 'video category',
                'description' => 'user can restore a video category',
                'user_id' => 1,
                'is_view_page' => false
            ],
            [
                'name' => 'admin/video-requests',
                'module' => 'video download request',
                'description' => 'user can view a download request page and change status of download request',
                'user_id' => 1,
                'is_view_page' => true
            ],
        ]);
    }
}
