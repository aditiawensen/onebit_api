var aw = {};
aw.hide = function (id) {
  aw.addStyle(id, "display", "none");
};
aw.show = function (id, a) {
  if (a) {aw.addStyle(a, "display", "none");}
  aw.addStyle(id, "display", "block");
};
aw.addStyle = function (id, prop, val) {
  var i, x = aw.getElements(id), l = x.length;
  for (i = 0; i < l; i++) {    
    x[i].style.setProperty(prop, val);
  }
};
aw.toggleShow = function (id) {
  var i, x = aw.getElements(id), l = x.length;
  for (i = 0; i < l; i++) {    
    if (x[i].style.display == "none") {
      aw.addStyle(x[i], "display", "block");
    } else {
      aw.addStyle(x[i], "display", "none");
    }
  }
};
aw.addClass = function (id, name) {
  var i, x = aw.getElements(id), l = x.length, arr1, arr2, j;
  for (i = 0; i < l; i++) {
    arr1 = x[i].className.split(" ");
    arr2 = name.split(" ");
    for (j = 0; j < arr2.length; j++) {
      if (arr1.indexOf(arr2[j]) == -1) {x[i].className += " " + arr2[j];}
    }
  }
};
aw.toggleClass = function (id, c1, c2) {
  var t1, t2, t1Arr, t2Arr, i, j, arr, x = aw.getElements(id), l = x.length, allPresent;
  t1 = (c1 || "");
  t2 = (c2 || "");
  t1Arr = t1.split(" ");
  t2Arr = t2.split(" ");
  for (i = 0; i < l; i++) {    
    arr = x[i].className.split(" ");
    if (t2Arr.length == 0) {
      allPresent = true;
      for (j = 0; j < t1Arr.length; j++) {
        if (arr.indexOf(t1Arr[j]) == -1) {allPresent = false;}
      }
      if (allPresent) {
        aw.removeClass(x[i], t1);
      } else {
        aw.addClass(x[i], t1);
      }
    } else {
      allPresent = true;
      for (j = 0; j < t1Arr.length; j++) {
        if (arr.indexOf(t1Arr[j]) == -1) {allPresent = false;}
      }
      if (allPresent) {
        aw.removeClass(x[i], t1);
        aw.addClass(x[i], t2);          
      } else {
        aw.removeClass(x[i], t2);        
        aw.addClass(x[i], t1);
      }
    }
  }
};
aw.removeClass = function (id, name) {
  var i, x = aw.getElements(id), l = x.length, arr1, arr2, j;
  for (i = 0; i < l; i++) {
    arr1 = x[i].className.split(" ");
    arr2 = name.split(" ");
    for (j = 0; j < arr2.length; j++) {
      while (arr1.indexOf(arr2[j]) > -1) {
        arr1.splice(arr1.indexOf(arr2[j]), 1);     
      }
    }
    x[i].className = arr1.join(" ");
  }
};
aw.getElements = function (id) {
  if (typeof id == "object") {
    return [id];
  } else {
    return document.querySelectorAll(id);
  }
};
aw.filterHTML = function(id, sel, filter) {
  var a, b, c, i, ii, iii, hit;
  a = aw.getElements(id);
  for (i = 0; i < a.length; i++) {
    b = aw.getElements(sel);
    for (ii = 0; ii < b.length; ii++) {
      hit = 0;
      if (b[ii].innerHTML.toUpperCase().indexOf(filter.toUpperCase()) > -1) {
        hit = 1;
      }
      c = b[ii].getElementsByTagName("*");
      for (iii = 0; iii < c.length; iii++) {
        if (c[iii].innerHTML.toUpperCase().indexOf(filter.toUpperCase()) > -1) {
          hit = 1;
        }
      }
      if (hit == 1) {
        b[ii].style.display = "";
      } else {
        b[ii].style.display = "none";
      }
    }
  }
};
aw.sortHTML = function(id, sel, sortvalue) {
  var a, b, i, ii, y, bytt, v1, v2, cc, j;
  a = aw.getElements(id);
  for (i = 0; i < a.length; i++) {
    for (j = 0; j < 2; j++) {
      cc = 0;
      y = 1;
      while (y == 1) {
        y = 0;
        b = a[i].querySelectorAll(sel);
        for (ii = 0; ii < (b.length - 1); ii++) {
          bytt = 0;
          if (sortvalue) {
            v1 = b[ii].querySelector(sortvalue).innerHTML.toLowerCase();
            v2 = b[ii + 1].querySelector(sortvalue).innerHTML.toLowerCase();
          } else {
            v1 = b[ii].innerHTML.toLowerCase();
            v2 = b[ii + 1].innerHTML.toLowerCase();
          }
          if ((j == 0 && (v1 > v2)) || (j == 1 && (v1 < v2))) {
            bytt = 1;
            break;
          }
        }
        if (bytt == 1) {
          b[ii].parentNode.insertBefore(b[ii + 1], b[ii]);
          y = 1;
          cc++;
        }
      }
      if (cc > 0) {break;}
    }
  }
};
aw.includeHTML = function() {
  var z, i, elmnt, file, xhttp;
  z = document.getElementsByTagName("*");
  for (i = 0; i < z.length; i++) {
    elmnt = z[i];
    file = elmnt.getAttribute("aw-include-html");
    if (file) {
      xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          elmnt.innerHTML = this.responseText;
          elmnt.removeAttribute("aw-include-html");
          aw.includeHTML();
        }
      }      
      xhttp.open("GET", file, true);
      xhttp.send();
      return;
    }
  }
};
aw.getHttpData = function (file, func) {
  aw.http(file, function () {
    if (this.readyState == 4 && this.status == 200) {
      func(this.responseText);
    }
  });
};
aw.getHttpObject = function (file, func) {
  aw.http(file, function () {
    if (this.readyState == 4 && this.status == 200) {
      func(JSON.parse(this.responseText));
    }
  });
};
aw.displayHttp = function (id, file) {
  aw.http(file, function () {
    if (this.readyState == 4 && this.status == 200) {
      aw.displayObject(id, JSON.parse(this.responseText));
    }
  });
};
aw.http = function (target, readyfunc, xml, method) {
  var httpObj;
  if (!method) {method = "GET"; }
  if (window.XMLHttpRequest) {
    httpObj = new XMLHttpRequest();
  } else if (window.ActiveXObject) {
    httpObj = new ActiveXObject("Microsoft.XMLHTTP");
  }
  if (httpObj) {
    if (readyfunc) {httpObj.onreadystatechange = readyfunc;}
    httpObj.open(method, target, true);
    httpObj.send(xml);
  }
};
aw.getElementsByAttribute = function (x, att) {
  var arr = [], arrCount = -1, i, l, y = x.getElementsByTagName("*"), z = att.toUpperCase();
  l = y.length;
  for (i = -1; i < l; i += 1) {
    if (i == -1) {y[i] = x;}
    if (y[i].getAttribute(z) !== null) {arrCount += 1; arr[arrCount] = y[i];}
  }
  return arr;
};  
aw.dataObject = {},
aw.displayObject = function (id, data) {
  var htmlObj, htmlTemplate, html, arr = [], a, l, rowClone, x, j, i, ii, cc, repeat, repeatObj, repeatX = "";
  htmlObj = document.getElementById(id);
  htmlTemplate = init_template(id, htmlObj);
  html = htmlTemplate.cloneNode(true);
  arr = aw.getElementsByAttribute(html, "aw-repeat");
  l = arr.length;
  for (j = (l - 1); j >= 0; j -= 1) {
    cc = arr[j].getAttribute("aw-repeat").split(" ");
    if (cc.length == 1) {
      repeat = cc[0];
    } else {
      repeatX = cc[0];
      repeat = cc[2];
    }
    arr[j].removeAttribute("aw-repeat");
    repeatObj = data[repeat];
    if (repeatObj && typeof repeatObj == "object" && repeatObj.length != "undefined") {
      i = 0;
      for (x in repeatObj) {
        i += 1;
        rowClone = arr[j];
        rowClone = aw_replace_curly(rowClone, "element", repeatX, repeatObj[x]);
        a = rowClone.attributes;
        for (ii = 0; ii < a.length; ii += 1) {
          a[ii].value = aw_replace_curly(a[ii], "attribute", repeatX, repeatObj[x]).value;
        }
        (i === repeatObj.length) ? arr[j].parentNode.replaceChild(rowClone, arr[j]) : arr[j].parentNode.insertBefore(rowClone, arr[j]);
      }
    } else {
      console.log("aw-repeat must be an array. " + repeat + " is not an array.");
      continue;
    }
  }
  html = aw_replace_curly(html, "element");
  htmlObj.parentNode.replaceChild(html, htmlObj);
  function init_template(id, obj) {
    var template;
    template = obj.cloneNode(true);
    if (aw.dataObject.hasOwnProperty(id)) {return aw.dataObject[id];}
    aw.dataObject[id] = template;
    return template;
  }
  function aw_replace_curly(elmnt, typ, repeatX, x) {
    var value, rowClone, pos1, pos2, originalHTML, lookFor, lookForARR = [], i, cc, r;
    rowClone = elmnt.cloneNode(true);
    pos1 = 0;
    while (pos1 > -1) {
      originalHTML = (typ == "attribute") ? rowClone.value : rowClone.innerHTML;
      pos1 = originalHTML.indexOf("{{", pos1);
      if (pos1 === -1) {break;}
      pos2 = originalHTML.indexOf("}}", pos1 + 1);
      lookFor = originalHTML.substring(pos1 + 2, pos2);
      lookForARR = lookFor.split("||");
      value = undefined;
      for (i = 0; i < lookForARR.length; i += 1) {
        lookForARR[i] = lookForARR[i].replace(/^\s+|\s+$/gm, ''); //trim
        if (x) {value = x[lookForARR[i]];}
        if (value == undefined && data) {value = data[lookForARR[i]];}
        if (value == undefined) {
          cc = lookForARR[i].split(".");
          if (cc[0] == repeatX) {value = x[cc[1]]; }
        }
        if (value == undefined) {
          if (lookForARR[i] == repeatX) {value = x;}
        }
        if (value == undefined) {
          if (lookForARR[i].substr(0, 1) == '"') {
            value = lookForARR[i].replace(/"/g, "");
          } else if (lookForARR[i].substr(0,1) == "'") {
            value = lookForARR[i].replace(/'/g, "");
          }
        }
        if (value != undefined) {break;}
      }
      if (value != undefined) {
        r = "{{" + lookFor + "}}";
        if (typ == "attribute") {
          rowClone.value = rowClone.value.replace(r, value);
        } else {
          aw_replace_html(rowClone, r, value);
        }
      }
      pos1 = pos1 + 1;
    }
    return rowClone;
  }
  function aw_replace_html(a, r, result) {
    var b, l, i, a, x, j;
    if (a.hasAttributes()) {
      b = a.attributes;
      l = b.length;
      for (i = 0; i < l; i += 1) {
        if (b[i].value.indexOf(r) > -1) {b[i].value = b[i].value.replace(r, result);}
      }
    }
    x = a.getElementsByTagName("*");
    l = x.length;
    a.innerHTML = a.innerHTML.replace(r, result);
  }
};