SELECT items.item_id, machine_type.machine_type_desc, sum(invoice_purchase_details.quantities) AS total,
 SUM(inventory.inventory_quantity) as spare,
 COUNT(item_borrow.borrow_status)
 FROM inventory 
LEFT JOIN invoice_purchase_details USING (item_id) 
LEFT JOIN items ON inventory.item_id = items.item_id
LEFT JOIN machine_type ON items.machine_type = machine_type.machine_type_id
LEFT JOIN item_borrow ON items.item_id = item_borrow.item_id
WHERE item_borrow.borrow_status = 0
GROUP BY machine_type.machine_type_desc
