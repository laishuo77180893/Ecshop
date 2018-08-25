create table user(
user_id int unsigned not null auto_increment primary key,
username varchar(16) not null default '',
email varchar(32) not null default '',
password char(32) not null default '',
regtime int unsigned not null default 0,
lastlogin int unsigned not null default 0
)engine myisam  charset utf8;





create table orderinfo(
order_id int unsigned not null primary key auto_increment,
order_sn char(15) not null default '',
use_id int not null default 0,
username varchar(20) not null default '',
zone varchar(30) not null default '',
address varchar(30) not null default '',
zipcode char(6) not null default '',
reciver varchar(10) not null default '',
email varchar(30) not null default '',
tel varchar(30) not null default '',
mobile char(11) not null default '',
best_time varchar(30) not null default '',
add_time int not null default 0,
order_amount decimal(9,2) not null default 0.0,
pay tinyint(1) not null default 0 
)engine myisam charset utf8;





create table ordergoods(
og_id int unsigned auto_increment primary key,	
order_id int unsigned not null default 0,
order_sn char(15) not null default '',
goods_id varchar(10) not null default '',
goods_name varchar(30) not null default '',
goods_number smallint not null default 1,
shop_price decimal(10,2) not null default 0.0,
subtotal decimal(10,2) not null default 0.0
)engine myisam charset utf8;










