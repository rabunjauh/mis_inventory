SELECT 
it.item_code, 
it.item_name, 
it.service_tag,
mt.machine_type_desc, 
ma.manufacture_desc,
mo.model_desc, 
os.operating_system_desc,
pr.processor_type,
me.memory_size, 
hd.hard_disk_size, 
it.item_id AS item_item_id,
inv.item_id AS inventory_item_id,
inv.inventory_quantity, 
inv.alert_qtt 
FROM items it
LEFT JOIN machine_type mt ON it.machine_type = mt.machine_type_id
LEFT JOIN manufacture ma ON it.manufacture = ma.manufacture_id
LEFT JOIN model mo ON it.model = mo.model_id
LEFT JOIN operating_system os ON it.operating_system = os.operating_system_id
LEFT JOIN processor pr ON it.processor = pr.processor_id
LEFT JOIN memory me ON it.memory = me.memory_id
LEFT JOIN hard_disk hd ON it.hdd = hd.hard_disk_id
JOIN inventory inv ON it.item_id = inv.item_id 