TRUNCATE TABLE `categories`;
TRUNCATE TABLE `category_details`;

INSERT INTO `categories` (`parent_id`, `lft`, `rght`, `name`, `created`, `modified`) VALUES
(NULL, 11, 48, 'Main Category', '2016-08-09 12:50:58', '2016-08-09 07:34:52'),
(1, 11, 48, 'Desktop Stationery', '2016-08-09 12:50:58', '2016-08-09 07:30:58'),
(1, 11, 48, 'Office Furniture', '2016-08-09 12:50:58', '2016-08-09 07:31:50'),
(1, 11, 48, 'Paper Products', '2016-08-09 12:50:58', '2016-08-09 07:32:30'),
(1, 11, 48, 'Writing Instruments', '2016-08-09 12:50:58', '2016-08-09 07:33:36'),
(1, 11, 48, 'Filing Accessories', '2016-08-09 12:50:58', '2016-08-09 07:34:31'),
(1, 11, 48, 'Display Solutions', '2016-08-09 12:50:58', '2016-08-09 07:34:52');


INSERT INTO `category_details` (`category_id`, `image`, `image_dir`, `created`, `modified`) VALUES
(2, '1.jpg', 'webroot\\img\\files\\CategoryDetails\\image\\', '2016-07-25 09:42:21', '2016-07-25 09:42:21'),
(3, '3.jpg', 'webroot\\img\\files\\CategoryDetails\\image\\', '2016-07-25 09:42:33', '2016-07-25 09:43:03'),
(4, 'desktop.jpg', 'webroot\\img\\files\\CategoryDetails\\image\\', '2016-08-09 07:30:58', '2016-08-09 07:30:58'),
(5, 'office.jpg', 'webroot\\img\\files\\CategoryDetails\\image\\', '2016-08-09 07:31:50', '2016-08-09 07:31:50'),
(6, 'paper.jpg', 'webroot\\img\\files\\CategoryDetails\\image\\', '2016-08-09 07:32:30', '2016-08-09 07:32:30'),
(7, 'writing.jpg', 'webroot\\img\\files\\CategoryDetails\\image\\', '2016-08-09 07:33:36', '2016-08-09 07:33:36');
