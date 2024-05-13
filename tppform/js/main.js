const mark_checks = async () => {
  console.log('Hi dev-human!');
  let all_checkboxes = document.getElementsByClassName('form-check-input');
  /*console.log('All checkboxes:');
  console.log(all_checkboxes);
  console.log(all_checkboxes.length);
  console.log([1,2,3].length);*/

  //let html_query_str = 'input[type="checkbox"][name="sad_co"]';

  //all_checkboxes.array.forEach
  all_checkboxes.forEach(e => {
    //console.log('Human Inside FOREACH');
    let checks = e.dataset.mydat;
    if(checks) {
      let cs_ar = checks.split('');
      switch(e.dataset.mynam) {
        case 'ent':
          e.checked = cs_ar[0] == '1' ? true : false;
          break;
        case 'cot':
          e.checked = cs_ar[1] == '1' ? true : false;
          break;
        case 'cer':
          e.checked = cs_ar[2] == '1' ? true : false;
          break;
      }
    }
  });
}

window.onload = () => {
//document.addEventListener('readystatechange', function () {   // TO TRY !!!!
  mark_checks();
//});
}
//$(html_query_str).checked = 'checked';
// maybe //$('.myCheckbox').removeAttr('checked')
//name_en/co/ce