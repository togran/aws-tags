/**
 * Created by Togran on 20.06.2018.
 */
function getRegionTags() {
  $('#t').remove();
  $('#t_wrapper').remove()
  $('.main-content').append('<div id="t">Loading...</div>')
  $.ajax('tags.php?action=list&region=' + $('#region').val(), {
    dataType: 'json',
    success: function (data) {
      $('#t').remove();
      $('.main-content').append('<table id="t" class="table-bordered"><thead></thead></table>')
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
      $('#allDates').click()
      addEditBtns()
      let indx=$('#t thead tr td').index($("td:contains('NeededUntil')"))
      $('#t').dataTable({
        order:[indx, 'asc'],
        columnDefs: [
          { "type": "mystring", "targets": indx }
        ]
      })
    },
    error:function(){
      $('#t').remove();
      $('.main-content').append('<div id="t">Something went wrong!</div>')
    }
  })
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
      console.log(new Date($(b).find('td:eq('+indx+')').text()))
      console.log(new Date())
      console.log(new Date($(b).find('td:eq('+indx+')').text())<new Date())
      if(new Date($(b).find('td:eq('+indx+')').text())<new Date()){
        $(b).addClass('table-danger')
      }else if(new Date($(b).find('td:eq('+indx+')').text())<new Date(new Date().getTime() + 24 * 60 * 60 * 1000)){
        $(b).addClass('table-warning')
      }
    }
  })
  $(this.parentNode).find('button').removeClass('active')
  $(this).addClass('active')
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
        $('#historyTable tbody').append('<tr><td>'+data[i][0]+'</td>'+'<td>'+data[i][1]+'</td>'+'<td>'+data[i][2]+'</td>'+'<td>'+data[i][3]+'</td></tr>')
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
  $('[id$=Dates]').click(checkDates)
  $('body').on('click', '.editTag', editTag)
  $('body').on('click', '.history', historyOfTag)
  $('body').on('click', '#changeDate', changeDate)
  $('#dateDialog').dialog()
  $('#dateDialog').dialog('close')
  $('#historyDialog').dialog({width:450})
  $('#historyDialog').dialog('close')
  $('#newDate').datepicker({dateFormat:'yy/mm/dd'})

  $.fn.dataTableExt.oSort['mystring-asc'] = function(x,y) {
    let retVal;
    x = $.trim(x);
    y = $.trim(y);
    if (x==y) retVal= 0;
    else if (x == "") retVal= 1;
    else if (y == "") retVal= -1;
    else if (x > y) retVal= 1;
    else retVal = -1; // <- this was ming in version 1
    return retVal;
  }
  $.fn.dataTableExt.oSort['mystring-desc'] = function(x,y) {
    let retVal;
    x = $.trim(x);
    y = $.trim(y);
    if (x==y) retVal= 0;
    else if (x == "") retVal= -1;
    else if (y == "") retVal= 1;
    else if (x < y) retVal= 1;
    else retVal = -1; // <- this was missing in version 1
    return retVal;
  }

})