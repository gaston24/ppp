
var btn = document.getElementById('btn_refresh');

btn.addEventListener('click',update);
btn.addEventListener("click", ()=>{$("#boxLoading").addClass("loading")});

function update()
{
    conexion = new XMLHttpRequest();
    conexion.onreadystatechange = ejecutarQuery;
    conexion.open("GET", "./Class/actualizar.php?estado=1", true);
    conexion.send();
}

function ejecutarQuery()
{
  if (conexion.readyState == 4) {
    $("#boxLoading").removeClass("loading");
    // console.log(conexion.responseText);
      Swal.fire({
        icon: 'success',
        title: 'Crédito de clientes actualizado exitosamente!',
        showConfirmButton: true,
      })
        // .then(function () {
        //     window.location = "index.php";
        // });
    } else {
      console.log('error');
    }
  
}

//Búsqueda rápida table//

function myFunction() {
    var input, filter, table, tr, td, td2, i, txtValue;
    input = document.getElementById("textBox");
    filter = input.value.toUpperCase();
    table = document.getElementById("table");
    tr = table.getElementsByTagName("tr");
    //tr = document.getElementById('tr');
  
    for (i = 0; i < tr.length; i++) {
      visible = false;
      /* Obtenemos todas las celdas de la fila, no sólo la primera */
      td = tr[i].getElementsByTagName("td");
  
      for (j = 0; j < td.length; j++) {
        if (td[j] && td[j].innerHTML.toUpperCase().indexOf(filter) > -1) {
          visible = true;
        } 
      }
      if (visible === true) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }

  






   

