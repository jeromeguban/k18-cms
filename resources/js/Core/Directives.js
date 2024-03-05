Vue.directive('can', {
    inserted: (el, action, vnode) => { 

      // Check if the user is the admin
      
      if( window.Laravel.admin ) {
          return null;
      }

      // Split the action
      // action[1] is the module name
      // action[2] is the permission name
      action = action.value.split('.');
      let request_module = action[1];
      let request_action = action[0];
      
      // Check if has permission
      // If module is not exist in permission or
      // If action is false
      // we will remove the element
      if(!window.Laravel.permissions[request_module] || !window.Laravel.permissions[request_module][request_action]) {
        return el.remove();
      }
    }
})