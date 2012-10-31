  var SrvDate = new Date();
  
  SrvDate.setFullYear(< %# DateTime.Now.Year %>);
  SrvDate.setMonth(< %# DateTime.Now.Month %> - 1);
  SrvDate.setDate(< %# DateTime.Now.Day %>);  
  SrvDate.setHours(< %# DateTime.Now.Hour %>);
  SrvDate.setMinutes(< %# DateTime.Now.Minute %>);
  SrvDate.setSeconds(< %# DateTime.Now.Second %>);
  
  var i = SrvDate.getSeconds();

  function timeGo() 
  {
    SrvDate.setSeconds(i);
  
    if (i >= 60) i = 1; 
    else i++;    
        
    second = SrvDate.getSeconds();
    hour = SrvDate.getHours();
    minute = SrvDate.getMinutes();
    day = SrvDate.getDate();
    month = SrvDate.getMonth() + 1;
    year = SrvDate.getFullYear();
    
    if (second < = 9) second = "0" + second;
    if (minute < = 9) minute = "0" + minute;
    if (hour < = 9) hour = "0" + hour;    
    if (day < = 9) day = "0" + day;
    if (month < = 9) month = "0" + month;        
    
    setTimeout("timeGo()", 1000);
    
    this.document.all.spanSrvDate.innerHTML = day + "." + month + "." + year + " " 
      + hour + ":" + minute + ":" + second;
  }

  timeGo();  var SrvDate = new Date();
  
  SrvDate.setFullYear(< %# DateTime.Now.Year %>);
  SrvDate.setMonth(< %# DateTime.Now.Month %> - 1);
  SrvDate.setDate(< %# DateTime.Now.Day %>);  
  SrvDate.setHours(< %# DateTime.Now.Hour %>);
  SrvDate.setMinutes(< %# DateTime.Now.Minute %>);
  SrvDate.setSeconds(< %# DateTime.Now.Second %>);
  
  var i = SrvDate.getSeconds();

  function timeGo() 
  {
    SrvDate.setSeconds(i);
  
    if (i >= 60) i = 1; 
    else i++;    
        
    second = SrvDate.getSeconds();
    hour = SrvDate.getHours();
    minute = SrvDate.getMinutes();
    day = SrvDate.getDate();
    month = SrvDate.getMonth() + 1;
    year = SrvDate.getFullYear();
    
    if (second < = 9) second = "0" + second;
    if (minute < = 9) minute = "0" + minute;
    if (hour < = 9) hour = "0" + hour;    
    if (day < = 9) day = "0" + day;
    if (month < = 9) month = "0" + month;        
    
    setTimeout("timeGo()", 1000);
    
    this.document.all.spanSrvDate.innerHTML = day + "." + month + "." + year + " " 
      + hour + ":" + minute + ":" + second;
  }

  timeGo();
