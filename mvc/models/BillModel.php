<?php
class BillModel extends DB
{
	public function insertBill($fullname, $phone, $email, $address, $note, $total, $method, $user_id, $created_at)
	{
		$insert = "INSERT INTO bill(fullname, phone, email, address, note,total, method, user_id, created_at) VALUES('$fullname', '$phone', '$email','$address', '$note','$total','$method', '$user_id', '$created_at')";
		return $this->pdo_execute_lastInsertID($insert);
	}

	public function insertDetailBill($id_pro, $image, $name_pro, $price, $number, $total, $id_bill, $created_at)
	{
		$insert = "INSERT INTO detail_bill(id_pro, image, name_pro, price, number, total, id_bill, created_at) VALUES ('$id_pro','$image','$name_pro','$price','$number','$total','$id_bill','$created_at' )";

		$this->pdo_execute($insert);
	}

	public function getAllBill($status = -1, $user_id = 0, $keyword = '')
	{
		$select = "SELECT * FROM bill WHERE 1 ";
		if ($status > -1) {
			$select .= " AND status = $status ";
		}
		if ($user_id > 0) {
			$select .= " AND user_id = $user_id ";
		}
		if ($keyword  != '') {
			$select .= " AND email like '%" . $keyword . "%' OR fullname like '%" . $keyword . "%' OR phone like '%" . $keyword . "%' OR address like '%" . $keyword . "%'";
		}
		$select .= " ORDER BY created_at DESC";
		return $this->pdo_query($select);
	}

	public function getDetailBill($id_bill)
	{
		$select = "SELECT * FROM detail_bill WHERE id_bill = $id_bill";
		return $this->pdo_query($select);
	}

	function SelectOneBill($id)
	{
		$select = "SELECT * FROM bill WHERE id = '$id'";
		if ($this->pdo_query_one($select)) {
			return $this->pdo_query_one($select);
		} else {
			return [];
		}
	}

	function editStatus($id, $status, $updated_at)
	{
		$sql = "UPDATE bill SET status= '$status', updated_at= '$updated_at' WHERE id= '$id' ";

		return $this->pdo_execute($sql);
	}

	public function deleteBill($id)
	{
		$detail_bill = "DELETE FROM detail_bill WHERE id_bill = $id";
		$bill = "DELETE FROM bill WHERE id = $id";
		$this->pdo_execute($detail_bill);
		return $this->pdo_execute($bill);
	}

	public function sum_bill(){
		$sum_money = "SELECT SUM(total) AS sum_bill FROM bill";
		return $this->pdo_query_value($sum_money);
	}
}
