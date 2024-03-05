Vue.filter('MMMM D, YYYY',(date)=>{
	if(date)
		return moment(String(date)).format('MMMM D, YYYY');
});

Vue.filter('MMM. D, YYYY',(date)=>{
	if(date)
		return moment(String(date)).format('MMM. D, YYYY');
});

Vue.filter('MMMM D, YYYY - h:mm:ss A',(date)=>{
	if(date)
		return moment(String(date)).format('MMMM D, YYYY - h:mm:ss A');
});

Vue.filter('ddd MMM. DD, YYYY - h:mm:ss A',(date)=>{
	if(date)
		return moment(String(date)).format('ddd MMM. DD, YYYY - h:mm:ss A');
});

Vue.filter('MMMM',(date)=>{
	if(date)
		return moment(String(date)).format('MMMM');
});

Vue.filter('MMM',(date)=>{
	if(date)
		return moment(String(date)).format('MMM');
});

Vue.filter('DD',(date)=>{
	if(date)
		return moment(String(date)).format('DD');
});

Vue.filter('dddd',(date)=>{
	if(date)
		return moment(String(date)).format('dddd');
});


Vue.filter('YYYY',(date)=>{
	if(date)
		return moment(String(date)).format('YYYY');
});

Vue.filter('lll',(date)=>{
	if(date)
		return moment(String(date)).format('lll');
});

Vue.filter('LLL',(date)=>{
	if(date)
		return moment(String(date)).format('LLL');
});

Vue.filter('LT',(date)=>{
	if(date)
		return moment(String(date)).format('LT');
});

Vue.filter('elapsedTime',(date)=>{
	if(date)
		return moment(String(date)).startOf('minute').fromNow();
});

Vue.filter('remainingHours',(date)=>{
	if(date)
		return moment(String(date)).endOf('hours').fromNow();
});

Vue.filter('limitDescription', (data, limit)=>{
	if(data){
		if(data.length<=limit)
			return data;

		return `${(data.substring(0,limit)).trim()}...`;
	}
});


Vue.filter('formatPad', (value, length = 10, padString = '0') => {
  if (value != null) {
    return value.toString().padStart(length, padString);
  }

  return '';
});

Vue.filter('moneyFormat', (val) => {
	if(val){
	    return accounting.formatMoney(parseFloat(val), {
			symbol   : '₱ ',
			thousand : ",",
			decimal  : ".",
	    });
	}

	return '₱ 0.00';
});

Vue.filter('moneyFormatLetter', function(value) {
	if (typeof value !== 'number') {
	  return value;
	}

	if (value >= 1e6) {
	  return (value / 1e6).toFixed(3) + 'M';
	}

	if (value >= 1e3) {
	  return (value / 1e3).toFixed(3) + 'K';
	}

	return value.toFixed(3);
  });

