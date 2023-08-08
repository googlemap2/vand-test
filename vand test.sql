CREATE TABLE `products`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NULL,
  `created` datetime(255) NULL DEFAULT now(),
  `code` varchar(255) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `code`(`code`) USING BTREE
);

CREATE TABLE `store_product`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `store` int NULL,
  `product` int NULL,
  `created` datetime(255) NULL DEFAULT now(),
  PRIMARY KEY (`id`)
);

CREATE TABLE `stores`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NULL,
  `user` int NULL,
  `created` datetime(255) NULL DEFAULT now(),
  `code` varchar(255) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `code`(`code`) USING BTREE
);

CREATE TABLE `users`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NULL,
  `user_name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT now(),
  `updated` datetime NULL ON UPDATE CURRENT_TIMESTAMP,
  `token` varchar(255) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `user_name`(`user_name`) USING BTREE
);

ALTER TABLE `store_product` ADD CONSTRAINT `store` FOREIGN KEY (`store`) REFERENCES `stores` (`id`);
ALTER TABLE `store_product` ADD CONSTRAINT `product` FOREIGN KEY (`product`) REFERENCES `products` (`id`);
ALTER TABLE `stores` ADD CONSTRAINT `user` FOREIGN KEY (`user`) REFERENCES `users` (`id`);

