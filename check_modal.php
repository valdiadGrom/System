<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>

<body>

	<style>
		#zatemnenie {
			background: rgba(102, 102, 102, 0.68);
			width: 100%;
			height: 100%;
			position: fixed;
			top: 0;
			left: 0;
			display: none;
			z-index: 9999999999;
		}


		window {
			position: absolute;
			top: 40%;
			left: 50%;
			width: 1200px;
			border: 1px solid #cecece;
			padding: 50px;
			transform: translate(-50%, -50%);
			background-color: #fafafa;
		}

		a.close_window {
			position: absolute;
			right: 10px;
			top: 4px;
			border: none;
			cursor: pointer;
		}

		button {
			cursor: pointer;
		}
	</style>

	<table id="FilterTable">
		<tr>
			<td>1</td>
			<td>2</td>
			<td>3</td>
		</tr>
		<tr>
			<td>4</td>
			<td>5</td>
			<td>6</td>
		</tr>
	</table>

	<div id="zatemnenie">
		<window> <a id="closeX" class="close_window">X</a>
			Вы выбрали цифру <li id="tyt"></li>

		</window>
	</div>


	<script>
		let table = document.getElementById("FilterTable");
		let closeX = document.getElementById("closeX");
		let modal_window = document.getElementById("zatemnenie");
		let tyt = document.getElementById("tyt");
		let td;

		table.addEventListener("click", function(event) {

			tr = event.target.closest("tr");
			if (!tr) return;
			tyt.textContent = tr.textContent;
			modal_window.style = "display:block";
		});

		closeX.addEventListener("click", function(func) {
			modal_window.style = "display:none";
		})
	</script>

</body>

</html>