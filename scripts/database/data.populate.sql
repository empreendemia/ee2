-- phpMyAdmin SQL Dump
-- version 3.4.5deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 14, 2012 at 10:18 AM
-- Server version: 5.1.58
-- PHP Version: 5.3.6-13ubuntu3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `empreendemia_dev`
--

--
-- Dumping data for table `ads`
--

INSERT INTO `ads` (`id`, `product_id`, `public`, `status`, `date_created`, `date_deadline`) VALUES
(258, 14009, 'all', 'active', '2012-03-07 16:36:14', '2025-03-12'),
(259, 14017, 'all', 'active', '2012-03-08 10:23:29', '2012-05-13'),
(260, 14018, 'all', 'active', '2012-03-08 10:23:55', '2012-04-13'),
(261, 14041, 'all', 'active', '2012-03-08 10:28:31', '2012-05-13'),
(262, 14043, 'all', 'inactive', '2012-03-12 10:48:45', '2012-04-17');

--
-- Dumping data for table `ads_cities`
--

INSERT INTO `ads_cities` (`id`, `ad_id`, `city_id`, `views`, `clicks`) VALUES
(21951, 258, 709, 0, 0),
(21952, 258, 1563, 2, 0),
(21953, 258, 5644, 0, 0),
(21954, 258, 6861, 7, 0),
(21955, 258, 8781, 1, 0),
(21956, 258, 9422, 49, 1),
(21957, 259, 1, 11, 0),
(21958, 259, 709, 0, 0),
(21959, 259, 1563, 0, 0),
(21960, 259, 5644, 0, 0),
(21961, 259, 6861, 0, 0),
(21962, 259, 7754, 15, 0),
(21963, 259, 8781, 0, 0),
(21964, 259, 9422, 14, 0),
(21965, 260, 1, 11, 0),
(21966, 260, 709, 0, 0),
(21967, 260, 1563, 0, 0),
(21968, 260, 5644, 0, 0),
(21969, 260, 6861, 0, 0),
(21970, 260, 7754, 15, 0),
(21971, 260, 8781, 0, 0),
(21972, 260, 9422, 14, 0),
(21973, 261, 1, 20, 0),
(21974, 261, 709, 0, 0),
(21975, 261, 1563, 0, 0),
(21976, 261, 5644, 0, 0),
(21977, 261, 6861, 0, 0),
(21978, 261, 7754, 12, 1),
(21979, 261, 8781, 0, 0),
(21980, 261, 9422, 12, 0),
(21981, 262, 1, 0, 0),
(21982, 262, 709, 0, 0),
(21983, 262, 1563, 0, 0),
(21984, 262, 2324, 0, 0),
(21985, 262, 5644, 0, 0),
(21986, 262, 6861, 0, 0),
(21987, 262, 7754, 0, 0),
(21988, 262, 8781, 0, 0),
(21989, 262, 9422, 0, 0);

--
-- Dumping data for table `ads_sectors`
--

INSERT INTO `ads_sectors` (`id`, `ad_id`, `sector_id`, `views`, `clicks`) VALUES
(1500, 258, 11, 0, 0),
(1501, 258, 12, 0, 0),
(1502, 258, 27, 2, 0),
(1503, 258, 16, 6, 0),
(1504, 258, 22, 3, 0),
(1505, 258, 32, 7, 0),
(1506, 258, 19, 41, 1),
(1507, 259, 2, 11, 0),
(1508, 259, 39, 15, 0),
(1509, 259, 26, 10, 0),
(1510, 259, 11, 0, 0),
(1511, 259, 12, 0, 0),
(1512, 259, 27, 0, 0),
(1513, 259, 16, 0, 0),
(1514, 259, 22, 2, 0),
(1515, 259, 35, 0, 0),
(1516, 259, 32, 0, 0),
(1517, 259, 19, 2, 0),
(1518, 260, 2, 11, 0),
(1519, 260, 39, 15, 0),
(1520, 260, 26, 10, 0),
(1521, 260, 11, 0, 0),
(1522, 260, 12, 0, 0),
(1523, 260, 27, 0, 0),
(1524, 260, 16, 0, 0),
(1525, 260, 22, 2, 0),
(1526, 260, 35, 0, 0),
(1527, 260, 32, 0, 0),
(1528, 260, 19, 2, 0),
(1529, 261, 23, 9, 0),
(1530, 261, 2, 11, 0),
(1531, 261, 39, 12, 1),
(1532, 261, 26, 10, 0),
(1533, 261, 11, 0, 0),
(1534, 261, 12, 0, 0),
(1535, 261, 27, 0, 0),
(1536, 261, 16, 0, 0),
(1537, 261, 22, 2, 0),
(1538, 261, 35, 0, 0),
(1539, 261, 32, 0, 0),
(1540, 261, 19, 0, 0),
(1541, 262, 23, 0, 0),
(1542, 262, 2, 0, 0),
(1543, 262, 5, 0, 0),
(1544, 262, 39, 0, 0),
(1545, 262, 26, 0, 0),
(1546, 262, 11, 0, 0),
(1547, 262, 12, 0, 0),
(1548, 262, 27, 0, 0),
(1549, 262, 16, 0, 0),
(1550, 262, 22, 0, 0),
(1551, 262, 35, 0, 0),
(1552, 262, 32, 0, 0),
(1553, 262, 19, 0, 0);

--
-- Dumping data for table `budgets`
--

INSERT INTO `budgets` (`id`, `user_id`, `company_id`, `message`, `products`, `status`, `date`) VALUES
(773, NULL, 12, 'Olá, Stark Industries!\r\n\r\nGostaria de fazer um pedido de orçamento dos produtos listados acima.\r\n\r\nGostaria também de saber o quanto ficaria para ter uma armadura com sistema interno de refrigeração.\r\n\r\nAtenciosamente', '14013,10;', 'notAnswered', '2012-03-08 10:07:04');

--
-- Dumping data for table `businesses`
--

INSERT INTO `businesses` (`id`, `user_id`, `company_id`, `to_company_id`, `rate`, `testimonial`, `date`) VALUES
(443, 8, 7, 10, '-', 'O sistema de publicidade deles é bem ruim. Já tentei usar e achei uma merda. Por isso que resolvi fazer minha própria rede para ter que fazer meu próprio sistema de publicidade.', '2012-03-07 16:11:38'),
(444, 14, 10, 7, '-', 'Esse moleque não manja nada. Concorrência desleal, fez nosso Orkut e nosso Google Plus irem para merda só porque a gente manda mal pra cacete.', '2012-03-07 16:14:24'),
(445, 2, 2, 16, '+', 'Ma oe! A ACME sempre foi um ótimo fornecedor para as manhãs dominicais da SBT, garantindo a alegria da criançada! Alegria, haha ha!', '2012-03-08 09:19:17'),
(446, 24, 16, 2, '+', 'Fala silviao beleza??? a sbt sempre foi nossa maior cliente somos muito gratos pela possibilidade de oferecer nossos melhores produtos para o silviao.', '2012-03-08 09:21:20'),
(447, 26, 16, 13, '+', 'Chuck Norris é sinistro! Melhor que seu amigo, esculacha seu marido.', '2012-03-08 10:31:30');

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `sector_id`, `city_id`, `reputation`, `name`, `slug`, `date_created`, `date_updated`, `plan`, `plan_expiration`, `type`, `profile`, `status`, `image`, `card_image`, `side_image`, `activity`, `description`, `phone`, `phone2`, `email`, `website`, `address_street`, `address_number`, `address_complement`, `about`, `slides_url`, `slides_embed`, `video_url`, `link_blog`, `link_youtube`, `link_vimeo`, `link_slideshare`, `link_facebook`, `contact_twitter`, `contact_skype`, `contact_msn`, `contact_gtalk`) VALUES
(1, 22, 6861, 0, 'Pegadinha do Mallandro S/A', 'pegadinha-do-mallandro-s-a', '2012-03-07 14:48:59', '2012-03-07 15:08:38', 'gratis', NULL, 'company', 'all', 'active', 'c245765d52.jpg', NULL, NULL, NULL, NULL, NULL, NULL, 'pegadinha@mallandro.com.br', 'www.mallandro.com.br', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 32, 9422, 0, 'Sistema Brasileiro de Televisão', 'sistema-brasileiro-de-televisao', '2012-03-07 14:59:17', '2012-03-07 15:10:50', 'gratis', NULL, 'company', 'seller', 'active', '81c7c92031.jpg', NULL, NULL, NULL, NULL, NULL, NULL, 'contato@sbt.com.br', 'www.sbt.com.br', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 16, 6861, 0, 'Rede Globo', 'rede-globo', '2012-03-07 15:03:45', '2012-03-07 15:13:54', 'gratis', NULL, 'company', 'buyer', 'active', 'ea453337ed.jpg', NULL, NULL, NULL, NULL, NULL, NULL, 'contato@globo.com', 'globo.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 22, 9422, 0, 'Grupo Newcomm', 'grupo-newcomm', '2012-03-07 15:08:30', '2012-03-07 15:12:21', 'gratis', NULL, 'company', 'all', 'active', 'fd91cda8e0.jpg', NULL, NULL, NULL, NULL, NULL, NULL, 'contato@gruponewcomm.com.br', 'http://www.gruponewcomm.com.br', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 11, 709, 0, 'Maravilha S/A', 'maravilha-s-a', '2012-03-07 15:17:52', '2012-03-07 15:20:07', 'gratis', NULL, 'company', 'all', 'active', '7da75a0975.jpg', NULL, NULL, NULL, NULL, NULL, NULL, 'contato@maravilha.com.br', 'http://www.maramaravilha.com.br/', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 22, 6861, 0, 'Magal Productions', 'magal-productions', '2012-03-07 15:20:12', '2012-03-07 15:21:31', 'gratis', NULL, 'company', 'seller', 'active', '12ae13d9e7.png', NULL, NULL, NULL, NULL, NULL, NULL, 'contato@magal.com.br', 'www.magal.com.br', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 19, 8781, 0, 'Facebook', 'facebook', '2012-03-07 15:23:47', '2012-03-07 15:25:59', 'gratis', NULL, 'company', 'all', 'active', 'b37265ded0.png', NULL, NULL, NULL, NULL, NULL, NULL, 'contato@facebook.com', 'facebook.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 32, 9422, 0, 'Rede Record', 'rede-record', '2012-03-07 15:33:43', '2012-03-07 15:40:49', 'gratis', NULL, 'company', 'all', 'active', '66b86dc2ed.jpg', NULL, NULL, NULL, NULL, NULL, NULL, 'contato@rederecord.r7.com', 'contato@rederecord.r7.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 12, 5644, 0, 'Evil Chicken', 'evil-chicken', '2012-03-07 15:45:52', '2012-03-07 15:49:47', 'gratis', NULL, 'company', 'buyer', 'active', 'fdeeffec2e.png', NULL, NULL, 'Futebol', 'Somos o time que o Van Der Fart consegue jogar decentemente.\r\nTemos Umpa Lumpas na zaga, mas o Bale salva o time.', '55 11 99997777', '55 60 70707070', 'evil@chickenmaster.com.br', 'www.evilchicken.com.br', 'Rua dos Vencedores', '47', 'Do outro lado da rua', NULL, NULL, NULL, NULL, 'evilchicken.flogao.com.br', NULL, NULL, NULL, NULL, NULL, 'evil.chicken', 'evilchicken@hotmail.com', 'evilchicken@gmail.com'),
(10, 19, 9422, 0, 'Google', 'google', '2012-03-07 15:46:05', '2012-03-07 16:20:15', 'gratis', NULL, 'company', 'all', 'active', '244008db10.jpg', NULL, NULL, 'Produtos Web', 'Somos empresa líder no mercado de desenvolvimento web. Gostamos muito de fazer sistemas de buscas, aplicações e redes sociais.', NULL, NULL, 'contato@google.com', 'google.com', 'Mairiporanga Gonzales', '19884', NULL, NULL, NULL, NULL, NULL, 'blogger.com', NULL, NULL, NULL, NULL, '@google', 'google', 'google@hotmail.com', 'google@google.com'),
(11, 27, 1563, 0, 'Falcão S/A', 'falcao-s-a', '2012-03-07 15:53:11', '2012-03-07 15:53:33', 'gratis', NULL, 'company', 'all', 'active', 'b5e20c18ce.jpg', NULL, NULL, NULL, NULL, NULL, NULL, 'contato@falcao.com.br', 'contato@falcao.com.br', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 26, 8781, 0, 'Stark Industries', 'stark-industries', '2012-03-07 16:41:08', '2012-03-07 16:41:24', 'gratis', NULL, 'company', 'all', 'active', 'ae6988afc9.jpg', NULL, NULL, NULL, NULL, NULL, NULL, 'contato@starkindustries.com', 'starkindustries.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, 35, 9422, 0, 'Norris Security Certification', 'norris-security-certification', '2012-03-07 16:45:34', '2012-03-07 16:49:59', 'gratis', NULL, 'company', 'all', 'active', '2094ef4763.jpg', NULL, NULL, 'Segurança na Porrada', 'Com Chuck Norris Segurança, sua integridade estará 100% garantida! Bandido bom é bandido espancado até a morte!', NULL, NULL, 'contato@norris.com', 'norris.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(14, 19, 9422, 0, 'Apple', 'apple', '2012-03-07 16:53:12', '2012-03-07 17:02:58', 'gratis', NULL, 'company', 'seller', 'active', 'f683f68c08.jpg', NULL, NULL, 'Brinquedos e Meninices', 'Focamos nossas pesquisas em copiar o que já existe no mercado e colocamos nossa marca nela para ela custar o dobro do que deveria. Somos conhecidos como Piratas do Vale do Silício pois gostamos de piratear, mas cobramos mais caro do que os originais.', NULL, NULL, 'contato@apple.com', 'apple.com', 'Rua do Vale do Silício', '2900', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(15, 39, 7754, 0, 'Gaspar Strip Corporation', 'gaspar-strip-corporation', '2012-03-07 17:04:13', '2012-03-14 10:07:33', 'gratis', NULL, 'company', 'all', 'active', '28aff976a9.jpg', NULL, NULL, NULL, NULL, NULL, NULL, 'strip.para.machos@gaspar.com', 'www.stripsopramacho.com.br', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(16, 26, 9422, 0, 'A.C.M.E. s/a', 'a-c-m-e-s-a', '2012-03-08 08:13:18', '2012-03-08 08:15:43', 'gratis', NULL, 'company', 'seller', 'active', 'e7b73e63fc.gif', NULL, NULL, NULL, NULL, NULL, NULL, 'contato@acme.com', 'acme.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(17, 19, 9422, 0, 'Locaweb', 'locaweb', '2012-03-08 09:29:06', '2012-03-08 09:35:16', 'gratis', NULL, 'company', 'seller', 'active', 'a2e5e02b72.jpg', NULL, NULL, NULL, NULL, NULL, NULL, 'contato@locaweb.com.br', 'locaweb.com.br', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(18, 19, 9422, 0, 'Itautec', 'itautec', '2012-03-08 09:30:08', '2012-03-08 09:31:08', 'gratis', NULL, 'company', 'seller', 'active', '1af0c91db3.jpg', NULL, NULL, NULL, NULL, NULL, NULL, 'contato@itautec.com.br', 'itautec.com.br', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(19, 19, 9422, 0, 'ItautecNet', 'itautecnet', '2012-03-08 09:33:50', '2012-03-08 09:34:06', 'gratis', NULL, 'company', 'buyer', 'active', '7d931eea3e.png', NULL, NULL, NULL, NULL, NULL, NULL, 'contato@itautecnet.com.br', 'itautecnet.com.br', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(20, 2, 1, 0, 'Omega Corp', 'omega-corp', '2012-03-08 10:09:26', '2012-03-08 10:13:22', 'premium', '2013-03-23', 'company', 'buyer', 'active', '12a03179b1.jpg', NULL, NULL, 'Venda de salmão', 'Criamos salmões bacanudos e cheios de ômega 3', NULL, NULL, 'omega@silva.com.br', 'www.omega.com.br', NULL, NULL, NULL, '<p>\r\n	Compre conosco o seu salm&atilde;o! Fazemos um pre&ccedil;o melhor que o do Z&eacute; da esquina.</p>', 'http://www.slideshare.net/RocketScientist21/salmons', '&lt;div style=&quot;width:425px&quot; id=&quot;__ss_7314172&quot;&gt; &lt;strong style=&quot;display:block;margin:12px 0 4px&quot;&gt;&lt;a href=&quot;http://www.slideshare.net/RocketScientist21/salmons&quot; title=&quot;Salmons&quot; target=&quot;_blank&quot;&gt;Salmons&lt;/a&gt;&lt;/strong&gt; &lt;iframe src=&quot;http://www.slideshare.net/slideshow/embed_code/7314172&quot; width=&quot;425&quot; height=&quot;355&quot; frameborder=&quot;0&quot; marginwidth=&quot;0&quot; marginheight=&quot;0&quot; scrolling=&quot;no&quot;&gt;&lt;/iframe&gt; &lt;div style=&quot;padding:5px 0 12px&quot;&gt; View more &lt;a href=&quot;http://www.slideshare.net/thecroaker/death-by-powerpoint&quot; target=&quot;_blank&quot;&gt;PowerPoint&lt;/a&gt; from &lt;a href=&quot;http://www.slideshare.net/RocketScientist21&quot; target=&quot;_blank&quot;&gt;RocketScientist21&lt;/a&gt; &lt;/div&gt; &lt;/div&gt;', 'http://www.youtube.com/watch?v=_o8gHlFJLvI&feature=fvst', NULL, NULL, NULL, NULL, NULL, '@millorb11', 'salmaocorp', NULL, NULL),
(21, 19, 9422, 0, 'Zézin Org de TI', 'zezin-org-de-ti', '2012-03-08 10:20:17', NULL, 'gratis', NULL, 'company', 'all', 'active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'zezin@org.com', 'orgdeti.com.br', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(22, 23, 1, 0, 'Oie', 'oie', '2012-03-08 10:26:46', NULL, 'gratis', NULL, 'company', 'all', 'active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(23, 5, 8781, 0, 'EmpreendeSaga', 'empreendesaga', '2012-03-08 10:58:48', NULL, 'gratis', NULL, 'company', 'all', 'active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(24, 5, 2324, 0, 'Cheeseburguer Corporation', 'cheeseburguer-corporation', '2012-03-09 10:08:48', NULL, 'premium', '2012-03-27', 'company', 'seller', 'active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `user_id`, `contact_id`, `date`) VALUES
(187602, 14, 8, '2012-03-07 16:05:37'),
(187603, 8, 14, '2012-03-07 16:06:05'),
(187604, 24, 2, '2012-03-08 09:12:09'),
(187605, 24, 10, '2012-03-08 09:12:36'),
(187606, 24, 3, '2012-03-08 09:13:01'),
(187607, 2, 24, '2012-03-08 09:15:27'),
(187608, 3, 24, '2012-03-08 09:15:52'),
(187609, 10, 24, '2012-03-08 09:17:44'),
(187610, 17, 23, '2012-03-08 09:38:46'),
(187611, 17, 24, '2012-03-08 09:38:53'),
(187612, 17, 25, '2012-03-08 09:38:58'),
(187613, 17, 26, '2012-03-08 09:39:02'),
(187614, 17, 22, '2012-03-08 09:39:08'),
(187615, 17, 18, '2012-03-08 09:39:15'),
(187616, 17, 19, '2012-03-08 09:39:19'),
(187617, 17, 20, '2012-03-08 09:39:23'),
(187618, 17, 21, '2012-03-08 09:39:27'),
(187619, 17, 16, '2012-03-08 09:39:33'),
(187620, 17, 13, '2012-03-08 09:39:39'),
(187621, 17, 14, '2012-03-08 09:39:44'),
(187622, 17, 15, '2012-03-08 09:39:51'),
(187623, 17, 12, '2012-03-08 09:39:58'),
(187624, 17, 10, '2012-03-08 09:40:04'),
(187625, 17, 8, '2012-03-08 09:40:09'),
(187626, 17, 11, '2012-03-08 09:40:14'),
(187627, 17, 7, '2012-03-08 09:40:20'),
(187628, 17, 6, '2012-03-08 09:40:26'),
(187629, 17, 3, '2012-03-08 09:40:32'),
(187630, 17, 5, '2012-03-08 09:40:36'),
(187631, 17, 4, '2012-03-08 09:40:43'),
(187632, 17, 2, '2012-03-08 09:40:49'),
(187633, 17, 9, '2012-03-08 09:40:53'),
(187634, 17, 1, '2012-03-08 09:40:59'),
(187635, 1, 17, '2012-03-08 09:43:47'),
(187636, 2, 17, '2012-03-08 09:44:20'),
(187637, 3, 17, '2012-03-08 09:44:47'),
(187638, 4, 17, '2012-03-08 09:45:00'),
(187639, 6, 17, '2012-03-08 09:45:15'),
(187640, 5, 17, '2012-03-08 09:45:25'),
(187641, 8, 17, '2012-03-08 09:45:41'),
(187642, 10, 17, '2012-03-08 09:46:05'),
(187643, 12, 17, '2012-03-08 09:46:19'),
(187644, 16, 17, '2012-03-08 09:46:30'),
(187645, 19, 17, '2012-03-08 09:46:42'),
(187646, 21, 17, '2012-03-08 09:47:01'),
(187647, 25, 17, '2012-03-08 09:47:53'),
(187648, 30, 22, '2012-03-08 10:19:02'),
(187649, 34, 24, '2012-03-08 10:30:12'),
(187651, 26, 17, '2012-03-08 10:30:56');

--
-- Dumping data for table `demands`
--

INSERT INTO `demands` (`id`, `user_id`, `sector_id`, `title`, `slug`, `price`, `description`, `description_2`, `description_3`, `description_4`, `status`, `date_created`, `date_deadline`) VALUES
(457, 22, 8, 'Fornecedor de sungas', 'fornecedor-de-sungas', '500', 'Preciso de um fornecedor de sungas bem pequenas de couro.', NULL, NULL, NULL, 'active', '2012-03-07 17:13:43', '2012-04-06'),
(458, 30, 2, 'Preciso de atum', 'preciso-de-atum', '0', 'Tenho uma indústria de salmões e gostaria de fornecedores de atum', NULL, NULL, NULL, 'active', '2012-03-08 10:35:51', '2012-04-30');

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `to_user_id`, `to_company_id`, `type`, `parent_id`, `title`, `body`, `body_2`, `body_3`, `body_4`, `date`, `status_sender`, `status_reader`) VALUES
(41091, 30, 22, NULL, 'user', NULL, 'Fala Gaspar!', 'Bacana seu perfil na rede. Vamos trocar cartões?', NULL, NULL, NULL, '2012-03-08 10:18:57', 'sent', 'unread');

--
-- Dumping data for table `notifies`
--

INSERT INTO `notifies` (`id`, `user_id`, `from_user_id`, `from_company_id`, `type`, `message`, `date`) VALUES
(112435, 17, 1, NULL, 'simple', 'Vocês trocaram seus cartões.', '2012-03-08 09:43:47'),
(112422, 13, 8, NULL, 'simple', 'Avaliou sua empresa', '2012-03-07 16:11:36'),
(112442, 17, 10, NULL, 'simple', 'Vocês trocaram seus cartões.', '2012-03-08 09:46:05'),
(112425, 11, 14, NULL, 'simple', 'Avaliou sua empresa', '2012-03-07 16:14:23'),
(112436, 17, 2, NULL, 'simple', 'Vocês trocaram seus cartões.', '2012-03-08 09:44:20'),
(112429, 23, 2, NULL, 'simple', 'Avaliou sua empresa', '2012-03-08 09:19:16'),
(112438, 17, 4, NULL, 'simple', 'Vocês trocaram seus cartões.', '2012-03-08 09:45:00'),
(112431, 25, 2, NULL, 'simple', 'Avaliou sua empresa', '2012-03-08 09:19:16'),
(112449, 17, 26, NULL, 'simple', 'Avaliou sua empresa', '2012-03-08 10:31:29'),
(112437, 17, 3, NULL, 'simple', 'Vocês trocaram seus cartões.', '2012-03-08 09:44:47'),
(112434, 9, 24, NULL, 'simple', 'Avaliou sua empresa', '2012-03-08 09:21:19'),
(112439, 17, 6, NULL, 'simple', 'Vocês trocaram seus cartões.', '2012-03-08 09:45:15'),
(112440, 17, 5, NULL, 'simple', 'Vocês trocaram seus cartões.', '2012-03-08 09:45:25'),
(112441, 17, 8, NULL, 'simple', 'Vocês trocaram seus cartões.', '2012-03-08 09:45:41'),
(112443, 17, 12, NULL, 'simple', 'Vocês trocaram seus cartões.', '2012-03-08 09:46:19'),
(112444, 17, 16, NULL, 'simple', 'Vocês trocaram seus cartões.', '2012-03-08 09:46:30'),
(112445, 17, 19, NULL, 'simple', 'Vocês trocaram seus cartões.', '2012-03-08 09:46:42'),
(112446, 17, 21, NULL, 'simple', 'Vocês trocaram seus cartões.', '2012-03-08 09:47:01'),
(112447, 17, 25, NULL, 'simple', 'Vocês trocaram seus cartões.', '2012-03-08 09:47:53'),
(112448, 17, 26, NULL, 'simple', 'Vocês trocaram seus cartões.', '2012-03-08 10:30:56');

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `company_id`, `name`, `slug`, `date_created`, `date_updated`, `description`, `website`, `image`, `sort`, `about`, `image_1`, `subtitle_1`, `image_2`, `subtitle_2`, `image_3`, `subtitle_3`, `image_4`, `subtitle_4`, `image_5`, `subtitle_5`, `offer_status`, `offer_description`, `offer_date_created`, `offer_date_deadline`) VALUES
(14004, 7, 'Timeline', 'timeline', '2012-03-07 15:27:22', '2012-03-07 15:34:04', 'Conte a história da sua vida em um novo tipo de perfil.', 'http://www.facebook.com/about/timeline', '83714114fd.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inactive', NULL, NULL, NULL),
(14005, 7, 'Anuncie', 'anuncie', '2012-03-07 15:29:17', '2012-03-08 08:00:23', 'Atinja seus clientes em potencial e aumente o número de fãs com Anúncios altamente direcionados', 'http://www.facebook.com/business/ads', 'b00a4deee7.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', 'Anuncie já no Facebook e ganhe totalmente de graça o bloqueio de todas as requisições para Meu Chip e Meu Calendário!', '2012-03-08 08:00:23', '2024-03-28'),
(14006, 7, 'Desenvolver para Sites', 'desenvolver-para-sites', '2012-03-07 15:31:57', '2012-03-07 15:31:57', 'Impulsione o crescimento e engajamento no seu site.', 'http://developers.facebook.com/docs/guides/web/', '20145512ce.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inactive', NULL, NULL, NULL),
(14007, 7, 'Desenvolver para Celular', 'desenvolver-para-celular', '2012-03-07 15:33:00', '2012-03-07 15:33:00', 'Permite que os usuários localizem e interajam com seus amigos através de aplicativos e jogos móveis', 'http://developers.facebook.com/docs/guides/mobile/', 'f12a3bbbc5.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inactive', NULL, NULL, NULL),
(14008, 9, 'Pimpolhice', 'pimpolhice', '2012-03-07 15:51:15', '2012-03-07 15:51:44', 'Tomar 3 a 0 só com gols de cabeça no time do Crouch', 'www.dafuq.com.br', 'f368791ab2.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', '4 a 0 com 30 minutos, se o Crouch estiver no banco e estiver jogando contra o Real Madrid.', '2012-03-07 15:51:44', '2012-03-31'),
(14009, 10, 'Google Ads', 'google-ads', '2012-03-07 16:22:28', '2012-03-07 16:22:28', 'Exiba seus anúncios no Google e em nossa rede de publicidade.', 'https://adwords.google.com', 'fdddafd537.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inactive', NULL, NULL, NULL),
(14010, 10, 'Google+', 'google', '2012-03-07 16:32:32', '2012-03-07 16:32:32', 'Compartilhamento da vida real\r\ncom a cara da web', 'http://www.google.com/+/learnmore/', 'ae16d059e2.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inactive', NULL, NULL, NULL),
(14011, 10, 'Orkut', 'orkut', '2012-03-07 16:33:49', '2012-03-07 16:33:57', 'Fale com todos os seus amigos ou apenas com grupos separados', 'http://www.orkut.com', '4f8eb0160f.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inactive', NULL, NULL, NULL),
(14012, 10, 'Gmail', 'gmail', '2012-03-07 16:35:14', '2012-03-07 16:35:14', 'Projetado com a ideia de que o e-mail pode ser mais intuitivo, eficiente, útil e até divertido', 'www.gmail.com', '50998b368d.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inactive', NULL, NULL, NULL),
(14013, 12, 'Iron Man', 'iron-man', '2012-03-07 16:43:28', '2012-03-08 08:09:33', 'Matador, muito matador! A armadura feita para matar! Para matar você de calor, seu safado!', NULL, '0db3fc0edf.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', 'Usuários Empreendemia ganham um desconto exclusivo na compra de uma armadura e ainda ganha totalmente de graça um Arc Reactor sobresaliente!', '2012-03-08 08:09:33', '2112-03-21'),
(14014, 13, 'Segurança Pessoal', 'seguranca-pessoal', '2012-03-07 16:47:09', '2012-03-07 16:47:09', 'Faço a sua segurança pessoal. Marco presença num raio de até 1.254 km e nenhum malandro chega perto', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inactive', NULL, NULL, NULL),
(14015, 13, 'Selo de Segurança de Dados', 'selo-de-seguranca-de-dados', '2012-03-07 16:47:49', '2012-03-07 16:47:58', 'Com meu selo de qualidade no seu computador, ninguém será louco de tentar mexer nele', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inactive', NULL, NULL, NULL),
(14016, 13, 'Selo de Segurança Doméstica', 'selo-de-seguranca-domestica', '2012-03-07 16:48:38', '2012-03-07 16:48:38', 'Com meu selo nas portas da sua casa, nenhum malandro terá coragem de voltar novamente', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inactive', NULL, NULL, NULL),
(14017, 15, 'Strip para homens', 'strip-para-homens', '2012-03-07 17:08:59', '2012-03-07 17:08:59', 'Especializado em strips para grupos de até 23 homens.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inactive', NULL, NULL, NULL),
(14018, 15, 'Pole dance', 'pole-dance', '2012-03-07 17:11:11', '2012-03-07 17:11:11', 'Pole dance com direito a piruetas na vara.', NULL, '5381de0a0f.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inactive', NULL, NULL, NULL),
(14019, 14, 'iPad', 'ipad', '2012-03-08 07:47:58', '2012-03-08 07:47:58', 'Uma prensadeira passou em cima de um iPhone e virou o tablet mais vendido do mundo!', NULL, '7a98a993d7.jpg', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inactive', NULL, NULL, NULL),
(14020, 14, 'iPad 2', 'ipad-2', '2012-03-08 07:48:39', '2012-03-08 07:48:39', 'Adicionamos algumas coisinhas a mais (principalmente o número 2) só para continuar vendendo.', NULL, '2fb8a05118.jpg', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inactive', NULL, NULL, NULL),
(14021, 14, 'iPad 3', 'ipad-3', '2012-03-08 07:49:12', '2012-03-08 07:49:12', 'Nenhuma novidade tecnológica, mas marketing o suficiente para fazer você comprar mais um', NULL, '484ef9a40e.jpg', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inactive', NULL, NULL, NULL),
(14022, 14, 'iPad 4', 'ipad-4', '2012-03-08 07:49:49', '2012-03-08 07:49:49', 'Totalmente novo, o iPad 4 agora faz a mesma coisa que os outros, mas vai encher nosso rabo', NULL, '9cf18e02dc.jpg', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inactive', NULL, NULL, NULL),
(14023, 14, 'iPad 5', 'ipad-5', '2012-03-08 07:50:24', '2012-03-08 07:51:45', 'Em 5 anos nosso produto continua o mesmo e continuamos vendendo pelo  mesmíssimo preço!', NULL, '44b652d7c3.jpg', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', 'Desconto exclusivo para usuários empreendemia! De R$ 2399,00 por R$ 2398,99! Aproveite, pois nunca damos desconto em porra niuma!', '2012-03-08 07:51:45', '2020-11-30'),
(14024, 3, 'Jornal Nacional', 'jornal-nacional', '2012-03-08 07:53:54', '2012-03-08 07:53:54', 'Informação, diversão, tecnologia e William Bonner num só lugar. Boa noite.', 'g1.globo.com/jornal-nacional', '7fe593a817.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inactive', NULL, NULL, NULL),
(14025, 3, 'TV Colosso', 'tv-colosso', '2012-03-08 07:54:59', '2012-03-08 07:54:59', 'O mais novo programa da TV brasileira como nunca visto antes!', NULL, '750c149d5c.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inactive', NULL, NULL, NULL),
(14026, 3, 'Malhação', 'malhacao', '2012-03-08 07:56:18', '2012-03-08 07:58:56', 'A série de TV com mais episódios e temporadas do mundo!', NULL, '4bbf9051ba.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', 'Anuncie já em nosso programa! De R$ 2,00 por R$ 0,99! Desconto exclusivo para usuários Empreendemia! E na compra de dois anúncios você ainda ganha um CD do Michel Teló!', '2012-03-08 07:58:56', '2042-03-22'),
(14027, 3, 'Mais você', 'mais-voce', '2012-03-08 07:56:58', '2012-03-08 07:56:58', 'Aprenda a cozinhar coisas que você nunca vai tentar fazer porque parece ruim e/ou difícil', NULL, '32ccc83915.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inactive', NULL, NULL, NULL),
(14028, 16, 'Pistola Desintegradora!', 'pistola-desintegradora', '2012-03-08 08:21:03', '2012-03-08 08:21:03', 'Perfeita para desintegrar a qualquer momento!', NULL, '843cc251a0.gif', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inactive', NULL, NULL, NULL),
(14029, 16, 'CÁpsulas de Terremoto!', 'cApsulas-de-terremoto', '2012-03-08 08:22:15', '2012-03-08 09:04:48', 'Divita-se fazendo seu prÓprio terremoto onde, quando e o quanto quiser!!!', NULL, '3b90581349.jpg', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', 'Na compra de um frasco, usuÁrios empreendemia ainda ganham totalmente gratuito mais um produto qualquer de nosso catÁlogo! consulte nossos vendedores!', '2012-03-08 09:04:48', '2040-03-06'),
(14030, 16, 'Axle Grease!', 'axle-grease', '2012-03-08 08:25:04', '2012-03-08 08:29:04', 'Lubrificante que pode ser usado em uma infinitude de possibilidades!', NULL, '276c286033.jpg', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inactive', NULL, NULL, NULL),
(14031, 16, 'Instant Girl!', 'instant-girl', '2012-03-08 08:26:55', '2012-03-08 08:26:55', 'FaÇa sua prÓpria mulher em segundos  com instant girl! use com moderaÇÃo!', NULL, '2e7f374537.jpg', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inactive', NULL, NULL, NULL),
(14032, 16, 'Rocket ROLLER STAKES!', 'rocket-roller-stakes', '2012-03-08 08:30:27', '2012-03-08 08:30:27', 'Apavore a garotada com nossos patins com foguetes!!!', NULL, '67789dd066.jpg', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inactive', NULL, NULL, NULL),
(14033, 16, 'Super Speed Vitamins!', 'super-speed-vitamins', '2012-03-08 08:31:50', '2012-03-08 08:31:50', 'Com nossas vitaminas de super velocidade vocÊ consegue correr muito mais! corra e compre e corra!', NULL, 'fe0b666e02.jpg', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inactive', NULL, NULL, NULL),
(14034, 16, 'Artifical Rock!', 'artifical-rock', '2012-03-08 08:53:12', '2012-03-08 08:53:12', 'DisfarÇa-se de uma pedra e ninguÉm nunca imaginarÁ que aquela pedra É vocÊ!', NULL, '488e35df7c.jpg', 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inactive', NULL, NULL, NULL),
(14035, 16, 'Dehydrated Bolders', 'dehydrated-bolders', '2012-03-08 08:55:03', '2012-03-08 08:55:03', 'Com apenas uma gotinha de Água, vocÊ transforma nossas pequenas pedras desitradatas em pedregulhos!', NULL, 'ec725b2949.jpg', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inactive', NULL, NULL, NULL),
(14036, 16, 'Hair Grower', 'hair-grower', '2012-03-08 08:55:50', '2012-03-08 08:55:50', 'Transforme cabelo curto em uma verdadeira cabeleira em um  piscar de olhos!', NULL, '5bf2a79cd1.jpg', 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inactive', NULL, NULL, NULL),
(14037, 16, 'Iron Carrot!', 'iron-carrot', '2012-03-08 08:56:43', '2012-03-08 08:56:43', 'Engane seus amigos com nossa cenoura de ferro, Ótimo para quebrar dentes!', NULL, '2bc79fa2ec.jpg', 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inactive', NULL, NULL, NULL),
(14038, 16, 'Tornado Kit!', 'tornado-kit', '2012-03-08 08:57:21', '2012-03-08 08:57:21', 'Tenha seu prÓprio tornado!', NULL, '722f0bb0d3.jpg', 11, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inactive', NULL, NULL, NULL),
(14039, 16, 'hi Speed Tonic', 'hi-speed-tonic', '2012-03-08 08:58:17', '2012-03-08 08:58:17', 'Se vocÊ precisa de velocidade agora, tome nosso tonico e saia correndo!', NULL, '6d237dc3f8.jpg', 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inactive', NULL, NULL, NULL),
(14040, 20, 'Salmão', 'salmao', '2012-03-08 10:17:04', '2012-03-08 10:33:24', 'Salmãozinho sensual com sabor de pamonha!', 'www.omega.com.br/salmao', '6b18410e66.jpg', NULL, '<p>\r\n	Al&eacute;m de ser bom pro sushi, serve para colocar no George Foreman e deixar o escrit&oacute;rio todo fedendo</p>', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', 'Desconto de 30% para usuários do Empreendemia', '2012-03-08 10:33:24', '2013-03-21'),
(14041, 22, 'Ma oe!', 'ma-oe', '2012-03-08 10:28:01', '2012-03-08 10:28:01', 'Serviços de falar "Ma oe!" para pessoas aleatórias na rua', NULL, 'ada380863b.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inactive', NULL, NULL, NULL),
(14042, 24, 'Dúvidas com contabilidade?', 'duvidas-com-contabilidade', '2012-03-09 10:19:15', '2012-03-09 10:19:15', 'Tire suas dúvidas com a gente via chat online sem custo!', NULL, '780ab8739a.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inactive', NULL, NULL, NULL),
(14043, 24, 'Como vender na mídia', 'como-vender-na-midia', '2012-03-12 10:45:20', '2012-03-12 10:46:33', 'Saiba como comecei a vender mais, através da minha agência de comunicação.', 'http://www.eldevik.com.br/', 'ad966794bf.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inactive', NULL, NULL, NULL);

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `company_id`, `login`, `password`, `group`, `date_created`, `date_updated`, `options`, `mails`, `name`, `family_name`, `image`, `description`, `job`, `phone`, `cell_phone`, `email`) VALUES
(1, 1, 'testes+1@empreendemia.com.br', '7f3f79aa445a213da9a902fb75501552e0bf5976', 'user', '2012-03-07', '2012-03-08 09:43:37', '1', '1', 'Sérgio Mallandro', 'Neiva Cavalcanti', '3a394839e0.jpg', NULL, NULL, NULL, NULL, 'testes+1@empreendemia.com.br'),
(2, 2, 'testes+2@empreendemia.com.br', '7f3f79aa445a213da9a902fb75501552e0bf5976', 'user', '2012-02-11', '2012-03-08 09:44:18', '1', '0', 'Silvio', 'Santos', 'c90d3d9ab7.jpg', NULL, NULL, NULL, NULL, 'testes+2@empreendemia.com.br'),
(3, 3, 'testes+3@empreendemia.com.br', '7f3f79aa445a213da9a902fb75501552e0bf5976', 'user', '2012-03-07', '2012-03-08 09:44:44', '1', '1', 'Roberto', 'Marinho', '3114972899.jpg', NULL, NULL, NULL, NULL, 'testes+3@empreendemia.com.br'),
(4, 4, 'testes+4@empreendemia.com.br', '7f3f79aa445a213da9a902fb75501552e0bf5976', 'user', '2012-03-07', '2012-03-08 11:01:36', '1', '0', 'Roberto', 'Justus', '455ffe627c.jpg', NULL, NULL, NULL, NULL, 'testes+4@empreendemia.com.br'),
(5, 3, 'testes+5@empreendemia.com.br', '7f3f79aa445a213da9a902fb75501552e0bf5976', 'user', '0000-00-00', '2012-03-08 09:45:23', '1111111111', '1111111111', 'William', 'Bonner', '2733d67b86.jpg', NULL, 'Editor Chefe JN', NULL, NULL, 'testes+5@empreendemia.com.br'),
(6, 5, 'testes+6@empreendemia.com.br', '7f3f79aa445a213da9a902fb75501552e0bf5976', 'user', '2012-03-07', '2012-03-08 09:45:08', '1', '0', 'Eliamary', 'Silva', '69f4ba7276.jpg', NULL, NULL, NULL, NULL, 'testes+6@empreendemia.com.br'),
(7, 6, 'testes+7@empreendemia.com.br', '7f3f79aa445a213da9a902fb75501552e0bf5976', 'user', '2012-03-07', '2012-03-07 15:21:21', '1', '1', 'Sidney', 'Magalhães', '3fee32ef2e.jpg', NULL, NULL, NULL, NULL, 'testes+7@empreendemia.com.br'),
(8, 7, 'testes+8@empreendemia.com.br', '7f3f79aa445a213da9a902fb75501552e0bf5976', 'user', '2012-03-07', '2012-03-08 09:45:39', '1', '1', 'Mark', 'Zuckerberg', '924c187a2e.jpg', NULL, NULL, NULL, NULL, 'testes+8@empreendemia.com.br'),
(9, 2, 'testes+9@empreendemia.com.br', '7f3f79aa445a213da9a902fb75501552e0bf5976', 'user', '0000-00-00', '2012-03-07 15:29:39', '1111111111', '1111111111', 'Moacyr', 'Franco', 'f025b9f983.jpg', NULL, 'Humorista', NULL, NULL, 'testes+9@empreendemia.com.br'),
(10, 8, 'testes+10@empreendemia.com.br', '7f3f79aa445a213da9a902fb75501552e0bf5976', 'user', '2012-03-07', '2012-03-08 09:45:59', '1', '0', 'Edir', 'Macedo', '20a13a4aff.jpg', NULL, NULL, NULL, NULL, 'testes+10@empreendemia.com.br'),
(11, 7, 'testes+11@empreendemia.com.br', '7f3f79aa445a213da9a902fb75501552e0bf5976', 'user', '0000-00-00', '2012-03-07 15:41:37', '1111111111', '1111111111', 'Sean', 'Timberlake', '7832930a5f.jpg', NULL, 'Co-fundador', NULL, NULL, 'testes+11@empreendemia.com.br'),
(12, 9, 'testes+12@empreendemia.com.br', '7f3f79aa445a213da9a902fb75501552e0bf5976', 'user', '2012-03-07', '2012-03-08 09:46:12', '1', '0', 'Evil Chicken', 'From Hell', '4710447cca.jpg', NULL, NULL, NULL, NULL, 'testes+12@empreendemia.com.br'),
(13, 10, 'testes+13@empreendemia.com.br', '7f3f79aa445a213da9a902fb75501552e0bf5976', 'user', '2012-03-07', '2012-03-08 14:50:11', '1', '1', 'Larry', 'Page', 'a2ff89b55a.jpg', NULL, NULL, NULL, NULL, 'testes+13@empreendemia.com.br'),
(14, 10, 'testes+14@empreendemia.com.br', '7f3f79aa445a213da9a902fb75501552e0bf5976', 'user', '0000-00-00', '2012-03-08 14:50:12', '1111111111', '1111111111', 'Sergey', 'Brin', '743cdbbf6d.jpg', 'Eu não gosto muito de fazer arroz. Somos do país do Bacon, apesar de eu não ter nascido nos EUA. Mas bacon com certeza é o futuro da nação.', 'C.t.o.', NULL, NULL, 'testes+14@empreendemia.com.br'),
(15, 11, 'testes+15@empreendemia.com.br', '7f3f79aa445a213da9a902fb75501552e0bf5976', 'user', '2012-03-07', '2012-03-08 10:20:56', '1', '0', 'Marcondes', 'Maia', '9c7130fc93.jpg', NULL, NULL, NULL, NULL, 'testes+15@empreendemia.com.br'),
(16, 12, 'testes+16@empreendemia.com.br', '7f3f79aa445a213da9a902fb75501552e0bf5976', 'user', '2012-03-07', '2012-03-08 09:46:28', '1', '0', 'Tony', 'Stark', 'a56024eeb7.jpg', NULL, NULL, NULL, NULL, 'testes+16@empreendemia.com.br'),
(17, 13, 'testes+17@empreendemia.com.br', '7f3f79aa445a213da9a902fb75501552e0bf5976', 'user', '2012-03-07', '2012-03-08 09:37:48', '1', '1', 'Chuck', 'Norris', '9fd819b21d.jpg', NULL, NULL, NULL, NULL, 'testes+17@empreendemia.com.br'),
(18, 14, 'testes+18@empreendemia.com.br', '7f3f79aa445a213da9a902fb75501552e0bf5976', 'user', '2012-03-07', '2012-03-08 07:40:47', '1', '1', 'Steve', 'Jobs', '0a48b3697a.png', NULL, 'Falecido', NULL, NULL, 'testes+18@empreendemia.com.br'),
(19, 14, 'testes+19@empreendemia.com.br', '7f3f79aa445a213da9a902fb75501552e0bf5976', 'user', '0000-00-00', '2012-03-08 09:46:39', '1111111111', '1111111111', 'Tim', 'Cook', '2725224fa8.jpg', NULL, 'CEO fazendo biquinho', NULL, NULL, 'testes+19@empreendemia.com.br'),
(20, 14, 'testes+20@empreendemia.com.br', '7f3f79aa445a213da9a902fb75501552e0bf5976', 'user', '0000-00-00', '2012-03-07 17:01:46', '1111111111', '1111111111', 'Steve', 'Wozniak', '5007563ee8.jpg', NULL, 'Co-fundador sangrando', NULL, NULL, 'testes+20@empreendemia.com.br'),
(21, 14, 'testes+21@empreendemia.com.br', '7f3f79aa445a213da9a902fb75501552e0bf5976', 'user', '0000-00-00', '2012-03-08 09:46:50', '1111111111', '1111111111', 'Steve', 'Wonder', 'daefce2217.jpg', NULL, 'Mais um Steve', NULL, NULL, 'testes+21@empreendemia.com.br'),
(22, 15, 'testes+22@empreendemia.com.br', '7f3f79aa445a213da9a902fb75501552e0bf5976', 'user', '2012-03-07', '2012-03-14 10:07:42', '1', '0', 'Lucas', 'Gaspar', '56b8a612ae.jpg', NULL, NULL, NULL, NULL, 'testes+22@empreendemia.com.br'),
(23, 16, 'testes+23@empreendemia.com.br', '7f3f79aa445a213da9a902fb75501552e0bf5976', 'user', '2012-03-08', '2012-03-08 11:46:53', '1', '1', 'Perna', 'Longa', '61e693ed62.JPG', 'HÁ anos no mercado pesquisando novos produtos e tendÊncias, sou apaixonado por tecnologia e inovaÇÃo.', 'Diretor executivo', NULL, NULL, 'testes+23@empreendemia.com.br'),
(24, 16, 'testes+24@empreendemia.com.br', '7f3f79aa445a213da9a902fb75501552e0bf5976', 'user', '0000-00-00', '2012-03-08 09:20:02', '1111111111', '1111111111', 'Wile', 'Coyote', '2e747483b9.png', NULL, 'Engenheiro de testes', NULL, NULL, 'testes+24@empreendemia.com.br'),
(25, 16, 'testes+25@empreendemia.com.br', '7f3f79aa445a213da9a902fb75501552e0bf5976', 'user', '0000-00-00', '2012-03-08 09:47:41', '1111111111', '1111111111', 'Road', 'Runner', '17c2c02f4c.png', NULL, 'Diretor de Segurança', NULL, NULL, 'testes+25@empreendemia.com.br'),
(26, 16, 'testes+26@empreendemia.com.br', '7f3f79aa445a213da9a902fb75501552e0bf5976', 'user', '0000-00-00', '2012-03-08 10:30:42', '1111111111', '1111111111', 'Daffy', 'Duck', '614d6b1f75.jpg', NULL, 'Diretor de Finanças', NULL, NULL, 'testes+26@empreendemia.com.br'),
(27, 17, 'testes+27@empreendemia.com.br', '7f3f79aa445a213da9a902fb75501552e0bf5976', 'user', '2012-03-08', '2012-03-08 09:34:26', '1', '1', 'Locaweb', 'Vip', NULL, NULL, NULL, NULL, NULL, 'testes+27@empreendemia.com.br'),
(28, 18, 'testes+28@empreendemia.com.br', '7f3f79aa445a213da9a902fb75501552e0bf5976', 'user', '2012-03-08', '2012-03-08 09:30:08', '1', '0', 'Itautec', 'Vip', NULL, NULL, NULL, NULL, NULL, 'testes+28@empreendemia.com.br'),
(29, 19, 'testes+29@empreendemia.com.br', '7f3f79aa445a213da9a902fb75501552e0bf5976', 'user', '2012-03-08', '2012-03-08 09:33:50', '1', '0', 'Itautecnet', 'Vip', NULL, NULL, NULL, NULL, NULL, 'testes+29@empreendemia.com.br'),
(30, 20, 'testes+30@empreendemia.com.br', '7f3f79aa445a213da9a902fb75501552e0bf5976', 'user', '2012-03-08', '2012-03-08 10:33:02', '1', '1', 'João', 'do Caminhão', NULL, NULL, NULL, NULL, NULL, 'testes+30@empreendemia.com.br'),
(31, 21, 'testes+31@empreendemia.com.br', '7f3f79aa445a213da9a902fb75501552e0bf5976', 'user', '2012-03-08', '2012-03-08 10:20:30', '1', '0', 'José', 'Neves', 'a79beb5581.jpg', NULL, NULL, NULL, NULL, 'testes+31@empreendemia.com.br'),
(32, 21, 'testes+32@empreendemia.com.br', '7f3f79aa445a213da9a902fb75501552e0bf5976', 'unconfirmed', '0000-00-00', NULL, '1111111111', '1111111111', 'Thiago', 'Neves', NULL, NULL, 'Filho', NULL, NULL, 'testes+32@empreendemia.com.br'),
(33, 21, 'testes+33@empreendemia.com.br', '7f3f79aa445a213da9a902fb75501552e0bf5976', 'unconfirmed', '0000-00-00', NULL, '1111111111', '1111111111', 'Millor', 'Testando', NULL, NULL, 'Teste', NULL, NULL, 'testes+33@empreendemia.com.br'),
(34, 22, 'testes+34@empreendemia.com.br', '7f3f79aa445a213da9a902fb75501552e0bf5976', 'user', '2012-03-08', '2012-03-08 10:26:46', '1', '0', 'Millor', 'Machado', NULL, NULL, NULL, NULL, NULL, 'testes+34@empreendemia.com.br'),
(35, 23, 'testes+35@empreendemia.com.br', '7f3f79aa445a213da9a902fb75501552e0bf5976', 'user', '2012-03-08', '2012-03-08 10:58:49', '1', '0', 'Leonardo', 'Fake', NULL, NULL, NULL, NULL, NULL, 'testes+35@empreendemia.com.br'),
(36, 24, 'lucas@empreendemia.com.br', '7f3f79aa445a213da9a902fb75501552e0bf5976', 'user', '2012-03-09', '2012-03-12 10:34:20', '1', '0', 'Lulu', 'Cheeseburguer', NULL, NULL, NULL, NULL, NULL, 'lucas@empreendemia.com.br');
