SELECT items.item_id, machine_type.machine_type_desc, sum(invoice_purchase_details.quantities) AS total,
 SUM(inventory.inventory_quantity) as spare,
 SUM(item_borrow_detail.quantities) AS borrowed
 FROM inventory 
LEFT JOIN invoice_purchase_details USING (item_id) 
LEFT JOIN items ON inventory.item_id = items.item_id
LEFT JOIN machine_type ON items.machine_type = machine_type.machine_type_id
LEFT JOIN item_borrow_detail ON items.item_id = item_borrow_detail.item_id
WHERE item_borrow_detail.return_quantity = 0
GROUP BY machine_type.machine_type_desc
