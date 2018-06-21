/**
 * Created by Togran on 20.06.2018.
 */
function getRegionTags() {
  $('#t').remove();
  $('body').append('<div id="t">Loading...</div>')
  $.ajax('tags.php?action=list&region=' + $('#region').val(), {
    dataType: 'json',
    success: function (data) {
      $('#t').remove();
      $('body').append('<table id="t"><thead></thead></table>')
      $('#t > thead').append('<tr><td>Instance/Tag</td></tr>')
      // console.log(data)
      let num = 0;
      let keys = {};
      if($('#allTags').prop('checked')) {
        for (let i in data.keys) {
          $('#t > thead > tr').append('<td>' + i + '</td>')
          keys[i] = num;
          num++;
        }
      }else{
        keys={
          'Contact':0,
          'Name':1,
          'Purpose':2,
          'RequestTicket':3,
          'Requester':4,
          'NeededUntil':5
        }
        for (let i in keys) {
          $('#t > thead > tr').append('<td>' + i + '</td>')
          num++
        }
      }
      console.log(keys, num);
      $('#t').append('<tbody></tbody>')
      for (let i in data.instances) {
        $('#t > tbody').append('<tr id="' + i + '"><td>' + i + '</td></tr>')
        for (let j = 0; j < num; j++) {
          $('#' + i).append('<td></td>')
        }
        for (let ii in data.instances[i]) {
          $('#' + i + ' td:nth(' + (keys[ii] + 1) + ')').text(data.instances[i][ii])
          // console.log(data.instances[i], ii)
        }
      }
      $('#byNU').click()
      if($('#t thead tr td').index($("td:contains('NeededUntil')"))<0){
        $('#byNU').hide()
      }else {
        $('#byNU').show()
      }
      addEditBtns()
    },
    error:function(){
      $('#t').remove();
      $('body').append('<div id="t">Something went wrong!</div>')
    }
  })
}

function sortTable(id, col) {
  if(col<0)return;
  var table, rows, switching, i, x, y, shouldSwitch;
  table = document.getElementById(id);
  switching = true;
  /* Make a loop that will continue until
   no switching has been done: */
  while (switching) {
    // Start by saying: no switching is done:
    switching = false;
    rows = table.getElementsByTagName("TR");
    /* Loop through all table rows (except the
     first, which contains table headers): */
    for (i = 1; i < (rows.length - 1); i++) {
      // Start by saying there should be no switching:
      shouldSwitch = false;
      /* Get the two elements you want to compare,
       one from current row and one from the next: */
      x = rows[i].getElementsByTagName("TD")[col];
      y = rows[i + 1].getElementsByTagName("TD")[col];
      // Check if the two rows should switch place:
      if(x===undefined || y===undefined){
        break;
      }
      if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
        // If so, mark as a switch and break the loop:
        shouldSwitch = true;
        break;
      }
    }
    if (shouldSwitch) {
      /* If a switch has been marked, make the switch
       and mark that a switch has been done: */
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
    }
  }
}

function checkDates() {
  let it=this;
  let indx=$('#t thead tr td').index($("td:contains('NeededUntil')"))
  $('tr').each(function(a,b){
    if(a==0)return;
    if(isNaN(Date.parse($(b).find('td:eq('+indx+')').text()))){
      if(it.id=='validDates')$(b).hide()
      if(it.id=='invalidDates')$(b).show()
      if(it.id=='allDates')$(b).show()
    }else {
      if(it.id=='validDates')$(b).show()
      if(it.id=='invalidDates')$(b).hide()
      if(it.id=='allDates')$(b).show()
    }
  })
}

function addEditBtns() {
  let indx=$('#t thead tr td').index($("td:contains('NeededUntil')"))
  if(indx<0)return;
  $('#t tr').each(function(a,b){
    if(a==0)return;
    $(b).find('td:eq('+indx+')').append('<button class="editTag"><i class="far fa-edit"></i></button><button class="history"><i class="fas fa-history"></i></button>')
  })
}

function editTag() {
  $('#dateDialog').dialog('open')
  $('#instanceId').text(this.parentNode.parentNode.firstChild.innerText)
  $('#newDate').val(this.parentNode.innerText)
  $('#oldDate').val(this.parentNode.innerText)
}

function historyOfTag() {
  $('#historyTable tbody').empty()
  $('#historyDialog').dialog('open')
  $('#historyLoader').show();
  let instanceId=this.parentNode.parentNode.firstChild.innerText
  $.ajax('tags.php?action=history&region=' + $('#region').val(), {
    type:'post',
    data:{
      instance:instanceId
    },
    dataType: 'json',
    success:function(data){
      // console.log(data)
      for(var i in data){
        $('#historyTable tbody').append('<tr><td>'+data[i][0]+'</td>'+'<td>'+data[i][2]+'</td>'+'<td>'+data[i][3]+'</td></tr>')
      }
      $('#historyLoader').hide();
    },
    error:function(){
      alert('Something went wrong!')
      $('#historyDialog').dialog('close')
    }
  })
}

function changeDate() {
  $.ajax('tags.php?action=newDate&region=' + $('#region').val(), {
    type:'post',
    data:{
      instance:$('#instanceId').text(),
      newDate:$('#newDate').val(),
      oldDate:$('#oldDate').val()
    },
    complete:function () {
      $('#dateDialog').dialog('close')
      getRegionTags();
    },
    error:function(){
      alert('Something went wrong!')
    }
  })
}

$(document).ready(function() {
  getRegionTags();
  $('#region,#allTags').on('change', getRegionTags)
  $('#byName').click(function(){
    sortTable('t', $('#t thead tr td').index($("td:contains('Name')")))
  })
  $('#byNU').click(function(){
    sortTable('t', $('#t thead tr td').index($("td:contains('NeededUntil')")))
  })
  $('[id$=Dates]').click(checkDates)
  $('body').on('click', '.editTag', editTag)
  $('body').on('click', '.history', historyOfTag)
  $('body').on('click', '#changeDate', changeDate)
  $('#dateDialog').dialog()
  $('#dateDialog').dialog('close')
  $('#historyDialog').dialog()
  $('#historyDialog').dialog('close')
})