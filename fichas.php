<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
	header('Location: login.php');
	exit;
}
if ($_SESSION["role"] == 'U') {
	header('Location: home.php');
	exit;
}
include 'functions.php';
$pdo = pdo_connect_mysql();
if (isset($_GET['n_cliente'])) {
$stmt = $pdo->prepare('SELECT notas.user_id, notas.ficha, notas.estado, notas.nota, notas.created_at, fichas.problema FROM fichas INNER JOIN notas WHERE notas.user_id = ? GROUP BY ficha');
    $stmt->execute([$_GET['n_cliente']]);
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "Cliente selecionado não tem número de cliente";
}
?>
<?= template_header('Fichas') ?>
<style>
    @import url("https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css");

.pcs:after {
  content: " pcs";
}
.cur:before {
  content: "$";
}
.per:after {
  content: "%";
}
// --------------------------
* {
  box-sizing: border-box;
}
body {
  padding: 0.2em 2em;
}
table {
  width: 100%;
  th {
    text-align: left;
    border-bottom: 1px solid #ccc;
  }
  th,
  td {
    padding: 0.4em;
  }
}

table.fold-table {
  > tbody {
    > tr.view {
      td,
      th {
        cursor: pointer;
      }
      td:first-child,
      th:first-child {
        position: relative;
        padding-left: 20px;
        &:before {
          position: absolute;
          top: 50%;
          left: 5px;
          width: 9px;
          height: 16px;
          margin-top: -8px;
          font: 16px fontawesome;
          color: #999;
          content: "\f0d7";
          transition: all 0.3s ease;
        }
      }
      &:nth-child(4n-1) {
        background: #eee;
      }
      &:hover {
        background: #000;
      }
      &.open {
        background: tomato;
        color: white;
        td:first-child,
        th:first-child {
          &:before {
            transform: rotate(-180deg);
            color: #333;
          }
        }
      }
    }

    > tr.fold {
      display: none;
      &.open {
        display: table-row;
      }
    }
  }
}

.fold-content {
  padding: 0.5em;
  h3 {
    margin-top: 0;
  }
  > table {
    border: 2px solid #ccc;
    > tbody {
      tr:nth-child(even) {
        background: #eee;
      }
    }
  }
}

</style>
<div class="content read">
    <?php if (empty($contacts)){
    ?>
    <h2>Cliente selecionado não tem fichas</h2>
    <style>
			#myTable {
				display: none;
			}

			#myTable th,
			#myTable td {
				display: none;
			}

			#myTable tr {
				display: none;
			}

			table {
				display: none;
			}

			td,
			th {
				display: none;
			}

			tr:nth-child(even) {
				display: none;
			}
		</style>
		<h2 style="color: #4a536e;" hidden>Fichas</h2>
	<table id="myTable" hidden>
		<input type="text" id="myInput" onkeyup="myFunction()" placeholder="Procurar" hidden>
		<a href="create_ficha1.php?n_cliente=<?= $_GET['n_cliente'] ?>" class="create-contact">Criar Ficha</a>
    <?php
} else { ?>
	<h2 style="color: #4a536e;">Fichas</h2>
	<table class="fold-table">
	    <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Procurar">
  <thead>
    <tr>
      <th>Company</th>
      <th>Amount</th>
      <th>Value</th>
      <th>Premiums</th>
      <th>Strategy A</th>
      <th>Strategy B</th>
      <th>Strategy C</th>
    </tr>
  </thead>
  <tbody>
    <tr class="view">
      <td>Company Name</td>
      <td class="pcs">457</td>
      <td class="cur">6535178</td>
      <td>-</td>
      <td class="per">50,71</td>
      <td class="per">49,21</td>
      <td class="per">0</td>
    </tr>
    <tr class="fold">
      <td colspan="7">
        <div class="fold-content">
          <h3>Company Name</h3>
          <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.</p>
          <table>
            <thead>
              <tr>
                <th>Company name</th>
                <th>Customer no</th>
                <th>Customer name</th>
                <th>Insurance no</th>
                <th>Strategy</th>
                <th>Start</th>
                <th>Current</th>
                <th>Diff</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Sony</td>
                <td>13245</td>
                <td>John Doe</td>
                <td>064578</td>
                <td>A, 100%</td>
                <td class="cur">20000</td>
                <td class="cur">33000</td>
                <td class="cur">13000</td>
              </tr>
              <tr>
                <td>Sony</td>
                <td>13288</td>
                <td>Claire Bennet</td>
                <td>064877</td>
                <td>B, 100%</td>
                <td class="cur">28000</td>
                <td class="cur">48000</td>
                <td class="cur">20000</td>
              </tr>
              <tr>
                <td>Sony</td>
                <td>12341</td>
                <td>Barry White</td>
                <td>064123</td>
                <td>A, 100%</td>
                <td class="cur">10000</td>
                <td class="cur">22000</td>
                <td class="cur">12000</td>
              </tr>
            </tbody>
          </table>
        </div>
      </td>
    </tr>
		<a href="create_ficha1.php?n_cliente=<?= $_GET['n_cliente'] ?>" class="create-contact">Criar Ficha</a>
	</table>
	<script>
		function myFunction() {
			var input, filter, table, tr, td, i;
			input = document.getElementById("myInput");
			filter = input.value.toUpperCase();
			table = document.getElementById("myTable");
			tr = table.getElementsByTagName("tr");
			for (i = 0; i < tr.length; i++) {
				td = tr[i].getElementsByTagName("td")[0]; // coluna n_cliente
				td1 = tr[i].getElementsByTagName("td")[1]; // coluna nome_cliente
				td2 = tr[i].getElementsByTagName("td")[2]; // coluna email_cliente
				td3 = tr[i].getElementsByTagName("td")[3]; // coluna telefone_cliente
				td4 = tr[i].getElementsByTagName("td")[4]; // coluna nif_cliente
				td5 = tr[i].getElementsByTagName("td")[5]; // coluna n_ficha
				td6 = tr[i].getElementsByTagName("td")[6]; // coluna estado
				td7 = tr[i].getElementsByTagName("td")[7]; // coluna nota
				td8 = tr[i].getElementsByTagName("td")[8]; // coluna problema
				if (td) {
					if ((td.innerHTML.toUpperCase().indexOf(filter) > -1) || (td8.innerHTML.toUpperCase().indexOf(filter) > -1) || (td7.innerHTML.toUpperCase().indexOf(filter) > -1) || (td6.innerHTML.toUpperCase().indexOf(filter) > -1) || (td5.innerHTML.toUpperCase().indexOf(filter) > -1) || (td1.innerHTML.toUpperCase().indexOf(filter) > -1) || (td2.innerHTML.toUpperCase().indexOf(filter) > -1) || (td3.innerHTML.toUpperCase().indexOf(filter) > -1) || (td4.innerHTML.toUpperCase().indexOf(filter) > -1)) {
						tr[i].style.display = "";
					} else {
						tr[i].style.display = "none";
					}
				}
			}
		}
		
$(function(){
  $(".fold-table tr.view").on("click", function(){
    $(this).toggleClass("open").next(".fold").toggleClass("open");
  });
});
	</script>
</div>
<?php }
?>
<?= template_footer() ?>