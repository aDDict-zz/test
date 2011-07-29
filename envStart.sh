#!/bin/bash

ifconfig eth0 192.168.0.107
/etc/rc.d/network restart

/etc/rc.d/mysqld start
/etc/rc.d/httpd start
/etc/rc.d/sshd start
/etc/rc.d/redis start
/usr/local/bin/searchd --config /usr/local/etc/sphinx.conf

mysql depo -u root --password=v  < /home/vvv/Desktop/sql/mem_filter_items.sql
mysql depo -u root --password=v  < /home/vvv/Desktop/sql/mem_product_keyword.sql
mysql depo -u root --password=v  < /home/vvv/Desktop/sql/mem_products_filter.sql
mysql depo -u root --password=v  < /home/vvv/Desktop/sql/mem_products.sql
