use djj_db;



select * from items where id=1;

-- show all tags in table
select C.apiName, C.category, T.tag
from Tags as T
left join Categories as C
on T.categoryID = C.id
;

-- show all tags, grouped by category into JSON array
select C.apiName, C.category, concat('["',group_concat(T.tag separator '","'),'"]') as tags
from TagCategories as TC
join Tags as T
on TC.tagID = T.id
left join Categories as C
on TC.categoryID = C.id
group by C.id
;

-- show all singular tags
select C.apiName, C.category, any_value(T.tag)
from TagCategories as TC
 join Tags as T
on TC.tagID = T.id
 join Categories as C
on TC.categoryID = C.id
group by C.id
having count(C.id) = 1
;

-- show all grouped tags
select C.apiName, C.category, concat('["',group_concat(T.tag separator '","'),'"]') as tags
from TagCategories as TC
join Tags as T
on TC.tagID = T.id
left join Categories as C
on TC.categoryID = C.id
group by C.id
having count(C.id) > 1
;

-- show all singular tags and grouped tags
select C.apiName, C.category, any_value(T.tag) as tag
from TagCategories as TC
 join Tags as T
on TC.tagID = T.id
 join Categories as C
on TC.categoryID = C.id
group by C.id
having count(C.id) = 1
union
select C.apiName, C.category, concat('["',group_concat(T.tag separator '","'),'"]') as tag
from TagCategories as TC
join Tags as T
on TC.tagID = T.id
left join Categories as C
on TC.categoryID = C.id
group by C.id
having count(C.id) > 1
;


-- show all tags in table by item id
select C.apiname, C.category, T.tag from itemtags as IT
left join Tags as T
on IT.tagID = T.id
left join categories as C
on T.categoryID = C.id
where IT.itemID = 1
;

-- show all tags by category
select C.*, T.* from tags as T
right join categories as C
on C.id = T.categoryID
order by C.category asc, T.tag asc
;

-- show all tags by category
select C.id as c_id, C.permalink as c_permalink, C.category, T.* from categories as C
left join tags as T
on C.id = T.categoryID
order by C.category asc, T.tag asc
;

-- get barcode products
select * from Barcodes;

-- search tags
select C.*, T.* from tags as T
			left join categories as C
			on T.categoryID = C.id
			where (lower(T.tag) LIKE regexp_replace('s', '[^[:alnum:]]+', ' ') or lower(C.category) LIKE lower('sp'))
			order by C.category asc, T.tag asc;
			
-- search tags with regex

select C.*, T.* from tags as T
			left join categories as C
			on T.categoryID = C.id
			where (
				regexp_replace(lower(T.tag), '[^[:alnum:]]+', '') LIKE '%spe%'
				or regexp_replace(lower(C.category), '[^[:alnum:]]+', '') LIKE '%spe%'
				)
			order by C.category asc, T.tag asc;


select C.*, T.* from tags as T
			left join categories as C
			on T.categoryID = C.id
		where regexp_replace(lower(T.tag), '[^[:alnum:]]+', ' ') LIKE '%s%';

select regexp_replace('s.p$e', '[^[:alnum:]]+', ' ')

-- get next auto)increment
select AUTO_INCREMENT from information_schema.TABLES
	where TABLE_SCHEMA = 'djj_db'
	and TABLE_NAME = 'items';
	
-- get last id
select last_insert_id() as id from Items;

-- get barcode queue
select * from barcodes;

-- get latest items
select * from items order by id desc limit 15;