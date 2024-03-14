use buecherei_mak;
Select buc_name,ver_start
from buchtitel buch
join exemplar exe on buch.buc_id = exe.buc_id
join verleih ver on exe.exe_id = ver.exe_id
where ver.ver_start >= '2024-02-01';