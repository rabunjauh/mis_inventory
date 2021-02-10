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
it.computer_name,
it.item_id AS item_item_id,
inv.item_id AS inventory_item_id,
inv.inventory_quantity, 
inv.alert_qtt 
FROM items it 
JOIN inventory inv ON it.item_id = inv.item_id 
JOIN machine_type mt ON it.machine_type = mt.machine_type_id
JOIN manufacture ma ON it.manufacture = ma.manufacture_id
JOIN model mo ON it.model = mo.model_id
JOIN operating_system os ON it.operating_system = os.operating_system_id
JOIN processor pr ON it.processor = pr.processor_id
JOIN memory me ON it.memory = me.memory_id
JOIN hard_disk hd ON it.hdd = hd.hard_disk_id