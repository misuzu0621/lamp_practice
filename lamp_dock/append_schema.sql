START TRANSACTION;

-- テーブルの構造 `orders`
CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- テーブルの構造 `order_details`
CREATE TABLE `order_details` (
  `order_detail_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- テーブルのインデックス `orders`
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY (`user_id`);

-- テーブルのインデックス `order_details`
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`order_detail_id`),
  ADD KEY (`order_id`),
  ADD KEY (`item_id`);

-- テーブルのAUTO_INCREMENT `orders`
ALTER TABLE `orders`
  MODIFY `order_id`int(11) NOT NULL AUTO_INCREMENT;

-- テーブルのAUTO_INCREMENT `order_details`
ALTER TABLE `order_details`
  MODIFY `order_detail_id` int(11) NOT NULL AUTO_INCREMENT;

-- テーブルの制約 `orders`
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

-- テーブルの制約 `order_details`
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`);
COMMIT;
