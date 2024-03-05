class Popup {	

  constructor() {

  }

  successPopup($data = { title: 'Success', text: 'Transaction Successful!' }) {
    swal({
      // title: $data.title,
      text: $data.text,
      type: 'success',
      timer: 3000,
    });
  }

  errorPopup($data = { title: 'Ooops', text: 'Something went wrong!' }) {
    swal({
      // title: $data.title, 
      text: $data.text, 
      icon: "error",
      button: false
    });
  }

  warningPopup($data = { title: 'Ooops', text: 'Something went wrong!' }) {
    swal({
      // title: $data.title, 
      text: $data.text, 
      icon: "warning",
      button: false
    });
  }

  confirmPopup(confirmation_message) {

    return new Promise((resolve, reject) => {
      swal({
        title: confirmation_message ? confirmation_message : 'Are you sure you want to delete this record?', 
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {

          

          resolve();

        } else {
          reject(false);
        }
      });
    });
  }

}

export default Popup;