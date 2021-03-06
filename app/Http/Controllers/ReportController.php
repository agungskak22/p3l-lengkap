<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Transaction;
use App\Procurement;
class ReportController extends Controller
{
    public function TransactionperYear($year)
    {
        $report = DB::select("SELECT MONTHNAME(STR_TO_DATE((m.bulan), '%m')) AS Bulan, COALESCE(SUM(d.detail_sparepart_subtotal),0) AS Sparepart,COALESCE(SUM(e.detail_service_subtotal),0) AS Service,COALESCE(SUM(d.detail_sparepart_subtotal),0)+COALESCE(SUM(e.detail_service_subtotal),0)  AS Total FROM (SELECT '01' AS
        bulan
        UNION SELECT '02' AS
        bulan
        UNION SELECT '03' AS
        bulan
        UNION SELECT '04' AS
        bulan
        UNION SELECT '05' AS
        bulan
        UNION SELECT '06' AS
        bulan
        UNION SELECT '07'AS
        bulan
        UNION SELECT '08'AS
        bulan
        UNION SELECT '09' AS
        bulan
        UNION SELECT '10' AS
        bulan
        UNION SELECT '11' AS
        bulan
        UNION SELECT '12' AS
        bulan
        )AS m LEFT JOIN (SELECT * FROM transactions WHERE YEAR(transaction_date)=$year AND transaction_paid='paid') p ON MONTHNAME(p.transaction_date) = MONTHNAME(STR_TO_DATE((m.bulan), '%m')) LEFT JOIN detail_spareparts d ON p.id_transaction=d.id_transaction
        LEFT JOIN detail_services e ON p.id_transaction=e.id_transaction
        GROUP BY YEAR(p.transaction_date),m.bulan
        ORDER by m.bulan");
        return response()->json($report, 200, [], JSON_NUMERIC_CHECK);
    }
    public function ExpenseperYear($year)
    {
        $report = DB::select("SELECT MONTHNAME(STR_TO_DATE((m.bulan), '%m')) AS Bulan,  COALESCE(SUM(d.subtotal),0) AS Total FROM (SELECT '01' AS
        bulan
        UNION SELECT '02' AS
        bulan
        UNION SELECT '03' AS
        bulan
        UNION SELECT '04' AS
        bulan
        UNION SELECT '05' AS
        bulan
        UNION SELECT '06' AS
        bulan
        UNION SELECT '07'AS
        bulan
        UNION SELECT '08'AS
        bulan
        UNION SELECT '09' AS
        bulan
        UNION SELECT '10' AS
        bulan
        UNION SELECT '11' AS
        bulan
        UNION SELECT '12' AS
        bulan
        )AS m LEFT JOIN (SELECT * FROM procurements WHERE YEAR(date)=$year)as p ON MONTHNAME(p.date) = MONTHNAME(STR_TO_DATE((m.bulan), '%m')) LEFT JOIN
        procurement_details d ON d.id_procurement = p.id_procurement
      
        GROUP BY YEAR(p.date),m.bulan
        ORDER by m.bulan");
        return response()->json($report, 200, [], JSON_NUMERIC_CHECK);
    }
    public function TransactionbyBranch()
    {
        $report = DB::select("SELECT YEAR(c.transaction_date) AS Tahun, d.branch_name AS Cabang, SUM(c.transaction_total) AS Total FROM employee_onduties a join employees b on b.id_employee=a.id_employee JOIN transactions c on c.id_transaction=a.id_transaction
        join branches d on d.id_branch=b.id_branch
        WHERE b.id_role = 1 or b.id_role = 2
        GROUP BY YEAR(c.transaction_date),d.branch_name");
        // return ($report);
        return response()->json($report, 200, [], JSON_NUMERIC_CHECK);
    }
    public function BestSellerSparepart()
    {
        $report = DB::select("SELECT MONTHNAME(STR_TO_DATE((m.bulan), '%m')) as Bulan, Coalesce((select s.sparepart_name from detail_spareparts t inner join spareparts s on t.id_sparepart = s.id_sparepart where MONTHNAME(t.created_at) = MONTHNAME(STR_TO_DATE((m.bulan), '%m')) group by t.id_sparepart order by sum(t.detail_sparepart_amount) DESC LIMIT 1),'-') AS NamaBarang, Coalesce((select tp.sparepart_type_name from detail_spareparts t inner join spareparts s on t.id_sparepart = s.id_sparepart inner join sparepart_types tp on s.id_sparepart_type = tp.id_sparepart_type where MONTHNAME(t.created_at) = MONTHNAME(STR_TO_DATE((m.bulan), '%m')) group by t.id_sparepart order by sum(t.detail_sparepart_amount) DESC LIMIT 1),'-') AS TipeBarang, Coalesce((select sum(detail_sparepart_amount) from detail_spareparts where MONTHNAME(created_at) = MONTHNAME(STR_TO_DATE((m.bulan), '%m')) group by id_sparepart order by sum(detail_sparepart_amount) DESC LIMIT 1),'-') AS JumlahPenjualan
        FROM(
               SELECT '01' AS
               bulan
               UNION SELECT '02' AS
               bulan
               UNION SELECT '03' AS
               bulan
               UNION SELECT '04' AS
               bulan
               UNION SELECT '05' AS
               bulan
               UNION SELECT '06' AS
               bulan
               UNION SELECT '07'AS
               bulan
               UNION SELECT '08'AS
               bulan
               UNION SELECT '09' AS
               bulan
               UNION SELECT '10' AS
               bulan
               UNION SELECT '11' AS
               bulan
               UNION SELECT '12' AS
               bulan
        ) AS m");
        return response()->json($report, 200, [], JSON_NUMERIC_CHECK);
    }
    public function ServiceSelling($year,$month)
    {
        $report = DB::select("SELECT motorcycle_brands.motorcycle_brand_name, motorcycle_types.motorcycle_type_name, services.service_name, sum(detail_services.detail_service_amount)as jumlah_jasa
        FROM detail_services
        LEFT JOIN motorcycles on detail_services.id_motorcycle = motorcycles.id_motorcycle
        JOIN motorcycle_types ON motorcycles.id_motorcycle_type = motorcycle_types.id_motorcycle_type
        JOIN motorcycle_brands ON motorcycle_types.id_motorcycle_brand = motorcycle_brands.id_motorcycle_brand
        LEFT JOIN services ON detail_services.id_service = services.id_service
        LEFT JOIN transactions ON detail_services.id_transaction = transactions.id_transaction
        WHERE YEAR(transactions.transaction_date) = $year
        AND Month(transactions.transaction_date) = $month
        AND transactions.transaction_paid = 'paid'
        GROUP BY detail_services.id_service, motorcycles.id_motorcycle
        ORDER BY motorcycles.id_motorcycle");
        return response()->json($report, 200, [], JSON_NUMERIC_CHECK);
    }
    public function RemainingStock($year,$sparepart)
    {
        $report = DB::select("SELECT MONTHNAME(STR_TO_DATE((m.bulan), '%m')) as 'Bulan', COALESCE((select (
            (select stock + (select sum(detail_sparepart_amount) from detail_spareparts join spareparts on detail_spareparts.id_sparepart=spareparts.id_sparepart
            where spareparts.id_sparepart_type = '$sparepart' and EXTRACT(YEAR FROM detail_spareparts.created_at) = $year)
             - (select sum(amount) from procurement_details join spareparts on procurement_details.id_sparepart=spareparts.id_sparepart where spareparts.id_sparepart_type = '$sparepart' and EXTRACT(YEAR FROM procurement_details.created_at) = $year) from spareparts where id_sparepart_type = '$sparepart')
             - (select sum(detail_sparepart_amount) from detail_spareparts join spareparts on detail_spareparts.id_sparepart=spareparts.id_sparepart where spareparts.id_sparepart_type = '$sparepart' and EXTRACT(Month FROM detail_spareparts.created_at) = bulan) 
            + (select sum(amount) from procurement_details join spareparts on procurement_details.id_sparepart=spareparts.id_sparepart where spareparts.id_sparepart_type = '$sparepart' and EXTRACT(Month FROM procurement_details.created_at) = bulan))  AS 'Jumlah Sparepart Sisa' 
            from spareparts where id_sparepart_type = '$sparepart'),'0') AS 'JumlahStokSisa'
             FROM(
                    SELECT '01' AS
                            bulan
                            UNION SELECT '02' AS
                            bulan
                            UNION SELECT '03' AS
                            bulan
                            UNION SELECT '04' AS
                            bulan
                            UNION SELECT '05' AS
                            bulan
                            UNION SELECT '06' AS
                            bulan
                            UNION SELECT '07'AS
                            bulan
                            UNION SELECT '08'AS
                            bulan
                            UNION SELECT '09' AS
                            bulan
                            UNION SELECT '10' AS
                            bulan
                            UNION SELECT '11' AS
                            bulan
                            UNION SELECT '12' AS
                            bulan
                        ) AS m");
        return response()->json($report, 200, [], JSON_NUMERIC_CHECK);
    }
    public function DetailSparepart($id)
    {
        $report = DB::select("SELECT t.id_transaction as id_transaction, t.transaction_discount as Diskon, s.id_sparepart as Kode, s.sparepart_name as Nama, s.merk as Merk, s.placement as Rak, d.detail_sparepart_amount as Jumlah, d.detail_sparepart_price as Harga_Satuan, d.detail_sparepart_subtotal as Harga_Total
        FROM transactions t 
        INNER JOIN detail_spareparts d ON d.id_transaction =  t.id_transaction
        INNER JOIN spareparts s ON s.id_sparepart = d.id_sparepart
        WHERE t.id_transaction = '$id'");
        return response()->json($report, 200, [], JSON_NUMERIC_CHECK);
    }
    public function DetailService($id)
    {
        $report = DB::select("SELECT t.id_transaction as id_transaction, t.transaction_discount as Diskon_Service, j2.id_service as KodeJasa, j2.service_name as NamaJasa, j.detail_service_amount as Jumlah, j.detail_service_price as Harga_Satuan_Service, j.detail_service_subtotal as Harga_Total_Service
        FROM transactions t 
        INNER JOIN detail_services j ON j.id_transaction = t.id_transaction
        INNER JOIN services j2 ON j2.id_service = j2.id_service
        WHERE t.id_transaction = '$id'");
        return response()->json($report, 200, [], JSON_NUMERIC_CHECK);
    }
    public function MotorSparepart($id)
    {
        $report = DB::select("SELECT t.id_transaction, m.motorcycle_brand_name as Merk, n.motorcycle_type_name as Tipe, p.license_number as Plat 
        FROM transactions t 
        INNER JOIN detail_spareparts d ON d.id_transaction =  t.id_transaction
        INNER JOIN motorcycles p ON p.id_motorcycle =  d.id_motorcycle
        INNER JOIN motorcycle_types n ON n.id_motorcycle_type = p.id_motorcycle_type
        INNER JOIN motorcycle_brands m ON m.id_motorcycle_brand = n.id_motorcycle_brand
        WHERE t.id_transaction = '$id'");
        return response()->json($report, 200, [], JSON_NUMERIC_CHECK);
    }
    public function MotorService($id)
    {
        $report = DB::select("SELECT t.id_transaction, m.motorcycle_brand_name as Merk, n.motorcycle_type_name as Tipe, p.license_number as Plat 
        FROM transactions t 
        INNER JOIN detail_services d ON d.id_transaction =  t.id_transaction
        INNER JOIN motorcycles p ON p.id_motorcycle =  d.id_motorcycle
        INNER JOIN motorcycle_types n ON n.id_motorcycle_type = p.id_motorcycle_type
        INNER JOIN motorcycle_brands m ON m.id_motorcycle_brand = n.id_motorcycle_brand
        WHERE t.id_transaction = '$id'");
        return response()->json($report, 200, [], JSON_NUMERIC_CHECK);
    }
    public function WorkOrder($id)
    {
        $report = DB::select("SELECT t.created_at as created_at, t.id_transaction as id_transaction, k.customer_name as Cust, k.customer_phone_number as Telepon
        FROM transactions t 
        INNER JOIN customers k ON k.id_customer = t.id_customer
        WHERE t.id_transaction = '$id'");
        return response()->json($report);
    }
    public function DataCS($id)
    {
        $report = DB::select("SELECT t.id_transaction, p.`name` as CS
        FROM transactions t 
        INNER JOIN employee_onduties m ON m.id_transaction =  t.id_transaction
        INNER JOIN employees p ON p.id_employee = m.id_employee
        WHERE p.id_role=2 && t.id_transaction = '$id'");
        return response()->json($report, 200, [], JSON_NUMERIC_CHECK);
    }
    public function MechanicSP($id)
    {
        $report = DB::select("SELECT t.id_transaction, p.name as Montir
        FROM transactions t 
        INNER JOIN detail_spareparts d ON d.id_transaction =  t.id_transaction
        INNER JOIN employees p ON p.id_employee = d.id_employee
        WHERE t.id_transaction = '$id'");
        return response()->json($report, 200, [], JSON_NUMERIC_CHECK);
    }
    public function MechanicSV($id)
    {
        $report = DB::select("SELECT t.id_transaction, p.`name` as Montir
        FROM transactions t 
        INNER JOIN detail_services d ON d.id_transaction =  t.id_transaction
        INNER JOIN employees p ON p.id_employee = d.id_employee
        WHERE t.id_transaction = '$id'");
        return response()->json($report, 200, [], JSON_NUMERIC_CHECK);
    }
    public function cetaksuratperintahkerja($id){
        $sparepart = DB::select("SELECT t.id_transaction as id_transaction, t.transaction_discount as Diskon, s.id_sparepart as Kode, s.sparepart_name as Nama, s.merk as Merk, s.placement as Rak, d.detail_sparepart_amount as Jumlah, d.detail_sparepart_price as Harga_Satuan, d.detail_sparepart_subtotal as Harga_Total
        FROM transactions t 
        INNER JOIN detail_spareparts d ON d.id_transaction =  t.id_transaction
        INNER JOIN spareparts s ON s.id_sparepart = d.id_sparepart
        WHERE t.id_transaction = '$id'");
        $service = DB::select("SELECT t.id_transaction as id_transaction, t.transaction_discount as Diskon_Service, j2.id_service as KodeJasa, j2.service_name as NamaJasa, j.detail_service_amount as Jumlah, j.detail_service_price as Harga_Satuan_Service, j.detail_service_subtotal as Harga_Total_Service
        FROM transactions t 
        INNER JOIN detail_services j ON j.id_transaction = t.id_transaction
        INNER JOIN services j2 ON j2.id_service = j2.id_service
        WHERE t.id_transaction = '$id'");
        $motorsparepart = DB::select("SELECT t.id_transaction, m.motorcycle_brand_name as Merk, n.motorcycle_type_name as Tipe, p.license_number as Plat 
        FROM transactions t 
        INNER JOIN detail_spareparts d ON d.id_transaction =  t.id_transaction
        INNER JOIN motorcycles p ON p.id_motorcycle =  d.id_motorcycle
        INNER JOIN motorcycle_types n ON n.id_motorcycle_type = p.id_motorcycle_type
        INNER JOIN motorcycle_brands m ON m.id_motorcycle_brand = n.id_motorcycle_brand
        WHERE t.id_transaction = '$id'");
        $motorservice = DB::select("SELECT t.id_transaction, m.motorcycle_brand_name as Merk, n.motorcycle_type_name as Tipe, p.license_number as Plat 
        FROM transactions t 
        INNER JOIN detail_services d ON d.id_transaction =  t.id_transaction
        INNER JOIN motorcycles p ON p.id_motorcycle =  d.id_motorcycle
        INNER JOIN motorcycle_types n ON n.id_motorcycle_type = p.id_motorcycle_type
        INNER JOIN motorcycle_brands m ON m.id_motorcycle_brand = n.id_motorcycle_brand
        WHERE t.id_transaction = '$id'");
        $customer = DB::select("SELECT t.created_at as created_at, t.id_transaction as id_transaction, k.customer_name as Cust, k.customer_phone_number as Telepon
        FROM transactions t 
        INNER JOIN customers k ON k.id_customer = t.id_customer
        WHERE t.id_transaction = '$id'");    
        $customerservice = DB::select("SELECT t.id_transaction, p.`name` as CS
        FROM transactions t 
        INNER JOIN employee_onduties m ON m.id_transaction =  t.id_transaction
        INNER JOIN employees p ON p.id_employee = m.id_employee
        WHERE p.id_role=2 && t.id_transaction = '$id'");
        $mechanicsparepart = DB::select("SELECT t.id_transaction, p.name as Montir
        FROM transactions t 
        INNER JOIN detail_spareparts d ON d.id_transaction =  t.id_transaction
        INNER JOIN employees p ON p.id_employee = d.id_employee
        WHERE t.id_transaction = '$id'");
        $mechanicjasa = DB::select("SELECT t.id_transaction, p.`name` as Montir
        FROM transactions t 
        INNER JOIN detail_services d ON d.id_transaction =  t.id_transaction
        INNER JOIN employees p ON p.id_employee = d.id_employee
        WHERE t.id_transaction = '$id'");
        return response()->json([
            'status' => (bool) $sparepart,
            'sparepart' => $sparepart,
            'customerservice' => $customerservice,
            'service' => $service,
            'motorsparepart' => $motorsparepart,
            'motorservice' => $motorservice,
            'customer' => $customer,
            'mechanicsparepart' => $mechanicsparepart,
            'mechanicjasa' => $mechanicjasa,
            'message' => $sparepart ? 'Success' : 'Error',
        ], 200, [], JSON_NUMERIC_CHECK);
    }
    public function CetakPemesanan($id)
    {
        $report = DB::select("select t.supplier_name, t.supplier_address, t.supplier_phone_number, p.date, sp.sparepart_name, sp.merk, d.subtotal, d.amount, a.sparepart_type_name from procurements p join procurement_details d on p.id_procurement=d.id_procurement join sales s on p.id_sales=s.id_sales JOIN suppliers t on s.id_supplier=t.id_supplier join spareparts sp on sp.id_sparepart=d.id_sparepart JOIN sparepart_types a ON a.id_sparepart_type = sp.id_sparepart_type
        where p.id_procurement='$id' and p.procurement_status='Finish'");
        return response()->json([
            'report' => $report ,
            'message' => $report ? 'Success' : 'Error' 
            , ], 200, [], JSON_NUMERIC_CHECK);
    }
}