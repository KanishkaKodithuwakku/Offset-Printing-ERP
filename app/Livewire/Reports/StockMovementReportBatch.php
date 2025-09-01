<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class StockMovementReportBatch extends Component
{
  public $movements = [];

  public function mount()
  {
    $this->loadMovements();
  }

  public function loadMovements()
  {
    // $this->movements = DB::select("
    //     SELECT
    //         stocks.table_name,
    //         stocks.items_id,
    //         items.item_name AS item_name,
    //         SUM(stocks.quantity) AS total_quantity,
    //         MAX(stocks.created_at) AS last_movement_date
    //     FROM
    //         stocks
    //     INNER JOIN
    //         items ON items.id = stocks.items_id
    //     GROUP BY
    //         stocks.table_name, stocks.items_id, items.item_name
    //     ORDER BY
    //         last_movement_date ASC, items.item_name ASC
    // ");
    $this->movements = DB::select(
      "SELECT
  i.item_code,
  i.item_name,
  s.table_name,
  s.created_at       AS last_movement_date,
  s.quantity         AS total_quantity,
  (
    SELECT COALESCE(SUM(q2.quantity),0)
    FROM stocks q2
    WHERE q2.items_id   = s.items_id
      AND q2.created_at < s.created_at
  )                   AS previous_balance,
  (
    SELECT SUM(q3.quantity)
    FROM stocks q3
    WHERE q3.items_id    = s.items_id
      AND q3.created_at <= s.created_at
  )                   AS current_balance
FROM stocks AS s
JOIN items  AS i 
  ON i.id = s.items_id
ORDER BY i.item_code   ASC, s.created_at ASC;
        "
    );
  }

  // Fetch stock movements based on filters
  public function render()
  {

    $bodyAttributes = 'x-data="{ page: \'StockMovementReportBatch\', loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
            x-init="darkMode = JSON.parse(localStorage.getItem(\'darkMode\'));
                    $watch(\'darkMode\', value => localStorage.setItem(\'darkMode\', JSON.stringify(value)))"
            :class="{\'dark bg-gray-900\': darkMode === true}"';


    return view('livewire.reports.stock-movement-report-batch')->layout('layouts.app', ['bodyAttributes' => $bodyAttributes]);
  }
}
