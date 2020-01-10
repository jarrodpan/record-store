use djj_db;



select * from items where id=1;

-- show all tags in table
select C.apiName, C.category, T.tag
from TagCategories as TC
join Tags as T
on TC.tagID = T.id
left join Categories as C
on TC.categoryID = C.id
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
left join tagcategories as TC
on TC.tagID = T.id
left join categories as C
on TC.categoryID = C.id
where IT.itemID = 1
;


select C.apiName, C.category, T.tag
from ItemTags as I
where itemID = 1
join  TagCategories as TC
join Tags as T
on TC.tagID = T.id
left join Categories as C
on TC.categoryID = C.id
;
