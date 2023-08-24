document.addEventListener("DOMContentLoaded", function () {
  let idStudent = null;
  let editStudent = false;

  //Mostrar alumnos
  function cargarAlumnos() {
    let xhr = new XMLHttpRequest();

    xhr.open("GET", "./load-student.php", true);

    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
        if (xhr.status === 200) {
          const response = JSON.parse(this.responseText);

          if (response.success) {
            let tbody = document.getElementById("t-body");
            tbody.innerHTML = "";

            response.data.forEach((student) => {
              let row = document.createElement("tr");

              let idCell = document.createElement("td");
              idCell.textContent = student.id;
              row.appendChild(idCell);

              let nombreCell = document.createElement("td");
              nombreCell.textContent = student.nombre;
              row.appendChild(nombreCell);

              let correoCell = document.createElement("td");
              correoCell.textContent = student.correo;
              row.appendChild(correoCell);

              tbody.appendChild(row);
            });
          } else {
            document.getElementById("respuesta").textContent = response.message;
          }
        } else {
          document.getElementById("respuesta").textContent =
            "Error en la solicitud al servidor";
        }
      }
    };

    xhr.send();
  }

  cargarAlumnos();

  //Agregar alumno o editar alumno
  document.getElementById("form").addEventListener("submit", function (e) {
    //Evitamos que se recargue la página - evitamos el comportamiento por
    //defecto del formulario
    e.preventDefault();

    //Recuperamos los datos
    let formData = new FormData(document.getElementById("form"));
    formData.append("id", idStudent);

    let url =
      editStudent === true ? "./edit-student.php" : "./insert-student.php";

    let xhr = new XMLHttpRequest();

    xhr.open("POST", url, true);

    xhr.onreadystatechange = function () {
      //La solictud ha sido procesada y la respuesta está lista para ser
      //utilizada (xhr.readyState === 4)
      //La solicitud se ha completado con éxito y el server (process.php)
      //ha devuelto una respuesta (xhr.status === 200)
      if (xhr.readyState === 4) {
        if (xhr.status === 200) {
          const response = JSON.parse(this.responseText);

          if (response.success) {
            document.getElementById("respuesta").textContent = response.message;

            //Para resetear el formulario
            document.getElementById("form").reset();
            editStudent = false;
            cargarAlumnos();
          } else {
            document.getElementById(
              "respuesta"
            ).textContent = `Error : ${response.message}`;
          }
        } else {
          document.getElementById("respuesta").textContent =
            "Error en la solicitud al servidor";
        }
      }
    };

    //Se envía al backend toda los datos del formulario
    xhr.send(formData);
  });

  //Remover alumno
  document.getElementById("remover").addEventListener("click", function () {
    let idInput = document.querySelector(".field[name='id']");
    let id = idInput.value.trim();

    let xhr = new XMLHttpRequest();

    xhr.open("POST", "./remove-student.php", true);

    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
        if (xhr.status === 200) {
          const response = JSON.parse(xhr.responseText);

          if (response.success) {
            cargarAlumnos();
            document.getElementById("respuesta").textContent = response.message;
          } else {
            document.getElementById("respuesta").textContent = response.message;
          }
        } else {
          document.getElementById("respuesta").textContent =
            "Error en la solicitud al servidor";
        }
      }
    };

    //Creación de objeto formdata, que contiene pares clave/valor
    //Se utiliza este objeto en una solicitud Ajax para enviar datos al servidor
    let formData = new FormData();
    formData.append("id", id);
    xhr.send(formData);
  });

  //Editar Alumno
  document.getElementById("editar").addEventListener("click", function () {
    let idInput = document.querySelector(".field[name='id']");
    idStudent = idInput.value.trim();

    let xhr = new XMLHttpRequest();

    //Pasando el valor del ID por la URL
    xhr.open("GET", `./getOne-student.php?id=${idStudent}`, true);

    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
        if (xhr.status === 200) {
          const response = JSON.parse(this.responseText);

          if (response.success) {
            document.getElementById("nombre").value = response.json.nombre;
            document.getElementById("apellido").value = response.json.apellido;
            document.getElementById("edad").value = response.json.edad;
            document.getElementById("correo").value = response.json.correo;

            editStudent = true;
          } else {
            document.getElementById("respuesta").textContent = response.message;
          }
        } else {
          document.getElementById("respuesta").textContent =
            "Error en la solicitud al servidor";
        }
      }
    };

    xhr.send();
  });
});
