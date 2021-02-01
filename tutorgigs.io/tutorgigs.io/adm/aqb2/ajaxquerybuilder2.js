var pa = "net.nickyb.ajaxquerybuilder";
window.onload = function () {
	qa.ra.sa("e", {
		ta: null
	}, ua, va);
	wa(document.getElementById("_a_"), "click", xa);
	wa(document.getElementById("_na_"), "click", ya);
	wa(document.getElementById("_h_"), "change", za.ab);
	wa(document.getElementById("_i_"), "change", za.bb);
	wa(document.getElementById("_k_"), "click", za.cb);
	wa(document.getElementById("_o_"), "click", db.cb);
	wa(document.getElementById("_s_"), "click", eb);
	wa(document.getElementById("_u_"), "click", fb);
	wa(document.getElementById("_v_"), "click", fb);
	wa(document.getElementById("_w_"), "click", fb);
	wa(document.getElementById("_aa_"), "click", gb);
	wa(document.getElementById("_ga_"), "click", gb);
	za.hb = document.getElementById("_e_");
	za.ib = document.getElementById("_g_");
	za.jb = document.getElementById("_j_");
	za.kb = document.getElementById("_i_");
	za.lb = document.getElementById("_f_");
	za.mb = document.getElementById("_h_");
	nb();
}
qa = {
	ob: 0,
	pb: 1,
	qb: 2,
	rb: 3,
	sb: 4,
	tb: 5,
	ub: 6,
	vb: 7,
	wb: 8,
	xb: 9,
	yb: new Array()
};
qa.ra = {
	sa: function (zb, ac, bc, cc) {
		var dc;
		if (window.XMLHttpRequest) {
			dc = new XMLHttpRequest();
		} else {
			dc = new ActiveXObject("Microsoft.XMLHTTP");
		}
		dc.onreadystatechange = function () {
			if (dc.readyState == 4) {
				if (dc.status == 200) {
					var ec = JSON.parse(dc.responseText);
					if (ec.fc) cc(ec);
					else bc(ec);
				} else {
					var ec = {
						fc: dc.status
					};
					cc(ec);
				}
			}
		}
		var json = JSON.stringify(ac);
		json = encodeURIComponent(json);
		dc.open("POST", "invoke", true);
		dc.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		if (dc.overrideMimeType) dc.overrideMimeType("text/xml; charset=ISO-8859-1");
		dc.send("procedure" + "=" + pa + "." + zb + "&" + "clientMessage" + "=" + json);
	}
}
qa.gc = {
	hc: function () {
		var ic = {
			jc: qa.ob,
			kc: this.lc(),
			mc: new Array()
		};
		return ic;
	},
	lc: function () {
		var nc = {
			jc: qa.pb,
			oc: this.pc(),
			qc: null
		};
		return nc;
	},
	pc: function () {
		var rc = {
			jc: qa.qb,
			sc: 0,
			tc: false,
			uc: new Array(),
			vc: new Array(),
			wc: new Array(),
			xc: new Array(),
			yc: new Array()
		};
		return rc;
	},
	zc: function (ta, name) {
		var ad = {
			jc: qa.rb,
			bd: ta,
			name: name,
			cd: null
		};
		return ad;
	},
	dd: function (ed, name) {
		var ad = {
			jc: qa.sb,
			fd: ed,
			name: name,
			cd: null
		};
		return ad;
	},
	gd: function (value) {
		var ad = {
			jc: qa.tb,
			value: value,
			cd: null
		};
		return ad;
	},
	hd: function () {
		var ad = {
			jc: qa.ub,
			jd: this.gd(null),
			kd: this.gd(null),
			ld: null,
			md: "="
		};
		return ad;
	},
	nd: function () {
		var ad = {
			jc: qa.vb,
			od: 0,
			pd: this.hd()
		};
		return ad;
	},
	qd: function (value) {
		var ad = {
			jc: qa.wb,
			value: value
		};
		return ad;
	},
	rd: function (value) {
		var ad = {
			jc: qa.xb,
			od: 0,
			value: value
		};
		return ad;
	}
}

function sd() {
	var td = 0,
		ud = 0;
	if (typeof (window.innerWidth) == 'number') {
		td = window.innerWidth;
		ud = window.innerHeight - 1;
	} else if (document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)) {
		td = document.documentElement.clientWidth;
		ud = document.documentElement.clientHeight;
	} else if (document.body && (document.body.clientWidth || document.body.clientHeight)) {
		td = document.body.clientWidth;
		ud = document.body.clientHeight;
	}
	return {
		width: td,
		height: ud
	};
}

function indexOf(array, vd, wd) {
	for (var xd = (wd || 0); xd < array.length; xd++) {
		if (array[xd] == vd) {
			return xd;
		}
	}
	return -1;
}

function yd(zd) {
	return zd.options[zd.selectedIndex].value;
}

function ae(zd) {
	return zd.options[zd.selectedIndex].text;
}

function be(zd, text) {
	if (text == null) return;
	for (var xd = 0; xd < zd.options.length; xd++) {
		if (zd.options[xd].text == text) zd.selectedIndex = xd;
	}
}

function ce(de, name) {
	var ee = document.getElementsByTagName(de);
	var fe = new Array();
	for (var xd = 0, ge = 0; xd < ee.length; xd++) {
		he = ee[xd].getAttribute("name");
		if (he == name) {
			fe[ge] = ee[xd];
			ge++;
		}
	}
	return fe;
}

function ie(id) {
	var je = document.getElementById("_a_");
	je.style.display = "block";
	je = document.getElementById(id);
	je.style.display = "block";
	return je;
}

function xa() {
	var je = document.getElementById("_a_");
	je.style.display = "none";
	je = document.getElementById("_d_");
	je.style.display = "none";
	je = document.getElementById("_l_");
	je.style.display = "none";
	je = document.getElementById("_p_");
	je.style.display = "none";
	je = document.getElementById("_b_");
	je.style.display = "none";
	je = document.getElementById("_c_");
	je.style.display = "none";
}

function ke(id) {
	var le = document.getElementById(id);
	le.style.display = "block";
	le.parentNode.childNodes[1].childNodes[1].className = "palettaOpen";
}

function me(id) {
	var le = document.getElementById(id);
	if (le.style.display == "none") {
		le.style.display = "block";
		le.parentNode.childNodes[1].childNodes[1].className = "palettaOpen";
	} else {
		le.style.display = "none";
		le.parentNode.childNodes[1].childNodes[1].className = "palettaClosed";
	}
}

function gb(ne) {
	var oe = pe(ne);
	ne.cancelBubble = true;
	if (ne.stopPropagation) ne.stopPropagation();
	me(oe.parentNode.parentNode.childNodes[3].id);
}

function ya() {
	var qe = document.getElementById("_ca_");
	if (qe.firstChild) {
		var json = JSON.stringify(qe.firstChild.re);
		json = encodeURIComponent(json);
		var se = document.getElementById("_oa_");
		se.src = "download?clientMessage=" + json;
	}
}
var db = {
	ue: function () {
		var hb = document.getElementById("_m_");
		var ve = document.getElementById("_n_");
		ve.value = we.parentNode.re.value;
		hb.innerHTML = "expression.edit";
		ie("_l_");
	},
	cb: function () {
		xa();
		var hb = document.getElementById("_m_");
		var ve = document.getElementById("_n_");
		if (hb.innerHTML == "expression.new") {
			xe(we.parentNode, ve.value);
		} else if (hb.innerHTML == "expression.edit") {
			var ad = we.parentNode.re;
			ad.value = ve.value;
			we.innerHTML = ad.value;
		}
	}
}
var za = {
	ab: function () {
		var md = yd(za.mb);
		if (md > 9 && md < 14) {
			za.kb.disabled = false;
		} else {
			za.kb.disabled = true;
			za.kb.checked = false;
			za.bb();
		}
	},
	ue: function () {
		za.ib.value = we.parentNode.re.jd.value;
		if (we.parentNode.re.kd.jc == qa.pb) {
			za.jb.value = "";
			za.jb.disabled = true;
			za.kb.checked = true;
		} else {
			za.jb.value = we.parentNode.re.kd.value;
			za.jb.disabled = false;
			za.kb.checked = false;
		}
		be(za.lb, we.parentNode.re.ld);
		be(za.mb, we.parentNode.re.md);
		za.hb.innerHTML = "condition.edit";
		ie("_d_");
	},
	cb: function () {
		xa();
		if (za.hb.innerHTML == "condition.new") {
			var ye = we.parentNode;
			var ze = qa.gc.gd(za.ib.value);
			var md = ae(za.mb);
			var af = za.kb.checked ? qa.gc.lc() : qa.gc.gd(za.jb.value);
			var ld = ye.childNodes[1].childNodes.length == 0 ? null : ae(za.lb);
			bf(ye, ze, md, af, ld);
		} else if (za.hb.innerHTML == "condition.edit") {
			var ye = we.parentNode.parentNode.parentNode;
			var ad = we.parentNode.re;
			ad.ld = ye.childNodes[1].childNodes[0] == we.parentNode ? null : ae(za.lb);
			ad.jd.value = za.ib.value;
			ad.md = ae(za.mb);
			if (za.kb.checked) {
				if (ad.kd.jc != qa.pb) {
					ad.kd = qa.gc.lc();
					cf(we.parentNode, ad.kd);
					we.parentNode.className = "expanded";
				}
				we.innerHTML = ad.jd.value + "&nbsp;<b>" + ad.md + "</b>&nbsp;<i>(SUBQUERY)</i>";
			} else {
				if (ad.kd.jc == qa.pb) {
					while (we.parentNode.childNodes[1].firstChild) we.parentNode.childNodes[1].removeChild(we.parentNode.childNodes[1].firstChild);
					we.parentNode.className = null;
				}
				ad.kd = qa.gc.gd(za.jb.value);
				we.innerHTML = ad.jd.value + "&nbsp;<b>" + ad.md + "</b>&nbsp;" + ad.kd.value;
			}
			if (ad.ld != null) we.innerHTML = "<b>" + ad.ld + "</b>&nbsp;" + we.innerHTML;
		}
		za.lb.selectedIndex = 0;
		za.mb.selectedIndex = 0;
		za.kb.checked = false;
		za.ib.value = "";
		za.jb.value = "";
		za.jb.disabled = false;
	},
	bb: function () {
		if (za.kb.checked) za.jb.value = "";
		za.jb.disabled = za.kb.checked;
	}
}

	function eb() {
		xa();
		var df = document.getElementById("_q_").value;
		var ef = document.getElementById("_r_").value;
		xe(ff(we), df + "(" + ef + ")");
	}

	function gf(ne) {
		var hf = pe(ne);
		ne.cancelBubble = true;
		if (ne.stopPropagation) ne.stopPropagation();
		if (hf.className == "expanded") {
			hf.className = "collapsed";
			hf.childNodes[1].style.display = "none";
		} else if (hf.className == "collapsed") {
			hf.className = "expanded";
			hf.childNodes[1].style.display = "block";
		}
	}

	function wa(jf, kf, lf) {
		if (!jf || jf === null) return;
		if (!kf || kf === null) return;
		if (!lf || lf === null) return;
		if (jf.addEventListener) {
			jf.addEventListener(kf, lf, false);
		} else if (jf.attachEvent) {
			jf.attachEvent("on" + kf, lf);
		} else {
			jf["on" + kf] = mf;
		}
	}

	function pe(ne) {
		return ne.target ? ne.target : ne.srcElement;
	}

	function nf(text, className, of) {
		var hf = document.createElement("li");
		var label = document.createElement("span");
		label.className = className;
		label.innerHTML = text;
		wa(label, "click", pf);
		hf.appendChild(label);
		if (of) {
			wa(hf, "click", gf);
			hf.appendChild(document.createElement("ul"));
		}
		return hf;
	}
var qf;

function nb() {
	var qe = document.getElementById("_ca_");
	if (qe.firstChild) qe.removeChild(qe.firstChild);
	var rf = document.createElement("ul");
	rf.className = "node";
	rf.re = qa.gc.hc();
	var sf = nf("ROOTQUERY", "node sql_query", true);
	sf.className = "expanded";
	sf.re = rf.re.kc;
	rf.appendChild(sf);
	cf(sf);
	qf = sf.id;
	var tf = nf("ORDER BY", "node sql_orderby", true);
	tf.re = rf.re.mc;
	rf.appendChild(tf);
	qe.appendChild(rf);
}

function uf(parentNode) {
	if (parentNode.re.qc != null) return;
	var vf = qa.gc.lc();
	parentNode.re.qc = vf;
	var wf = nf("UNION", "node sql_query", true);
	wf.className = "expanded";
	wf.re = vf;
	parentNode.childNodes[1].appendChild(wf);
	cf(wf);
}

function xf(parentNode) {
	var vf = qa.gc.lc();
	parentNode.re.push(vf);
	var wf = nf("SUBQUERY", "node sql_query", true);
	wf.className = "expanded";
	wf.re = vf;
	parentNode.childNodes[1].appendChild(wf);
	cf(wf);
}
var yf = 0;

function cf(parentNode, zf) {
	parentNode.id = "Graphic_" + (yf++);
	qa.yb[parentNode.id] = {
		ag: new Array(),
		bg: new Array(),
		cg: new Array()
	};
	if (!zf) zf = parentNode.re;
	var wf = nf("SELECT", "node sql_select", true);
	wf.re = zf.oc.uc;
	parentNode.childNodes[1].appendChild(wf);
	wf = nf("FROM", "node sql_from", true);
	wf.re = zf.oc.vc;
	parentNode.childNodes[1].appendChild(wf);
	wf = nf("WHERE", "node sql_search", true);
	wf.re = zf.oc.wc;
	parentNode.childNodes[1].appendChild(wf);
	wf = nf("GROUP BY", "node sql_groupby", true);
	wf.re = zf.oc.xc;
	parentNode.childNodes[1].appendChild(wf);
	wf = nf("HAVING", "node sql_search", true);
	wf.re = zf.oc.yc;
	parentNode.childNodes[1].appendChild(wf);
}

function xe(parentNode, value) {
	var ad = qa.gc.gd(value);
	parentNode.re.push(ad);
	var wf = nf(ad.value, "node sql_expression", true);
	wf.re = ad;
	parentNode.childNodes[1].appendChild(wf);
	parentNode.className = "expanded";
}

function dg(parentNode, ta, eg) {
	var ad = qa.gc.zc(ta, eg);
	parentNode.re.push(ad);
	var wf = nf(ad.name, "node sql_table", true);
	wf.re = ad;
	parentNode.childNodes[1].appendChild(wf);
	parentNode.className = "expanded";
	return ad;
}

function fg(parentNode, ta, eg) {
	var gg = ff(we);
	for (var xd = 0; xd < gg.childNodes[1].childNodes.length; xd++) {
		var hg = gg.childNodes[1].childNodes[xd].re;
		if (hg.jc == qa.sb) {
			if (hg.fd.name == eg) {
				ig(gg.childNodes[1].childNodes[xd--].firstChild);
			}
		}
	}
	ig(jg(eg));
}

function kg(ad) {
	if (ad.jc != qa.vb) alert("This is not a relation");
	var lg = mg(we);
	var ng = og(we);
	var array = ng.re;
	if (array.indexOf(ad) != -1) array.splice(array.indexOf(ad), 1);
	for (var xd = 0; xd < qa.yb[lg.id].ag.length; xd++) {
		if (qa.yb[lg.id].ag[xd][1] == ad.pd.jd.fd) {
			var pg = false;
			for (var qg = 0; qg < array.length; qg++) {
				if (array[qg].jc == qa.vb) {
					if (ad.pd.jd.fd == array[qg].pd.jd.fd || ad.pd.jd.fd == array[qg].pd.kd.fd) pg = true;
				}
			}
			if (!pg) array.push(ad.pd.jd.fd);
		}
		if (qa.yb[lg.id].ag[xd][1] == ad.pd.kd.fd) {
			var pg = false;
			for (var qg = 0; qg < array.length; qg++) {
				if (array[qg].jc == qa.vb) {
					if (ad.pd.kd.fd == array[qg].pd.jd.fd || ad.pd.kd.fd == array[qg].pd.kd.fd) pg = true;
				}
			}
			if (!pg) array.push(ad.pd.kd.fd);
		}
	}
}

function rg(parentNode, ta, eg, sg) {
	var tg = qa.gc.zc(ta, eg);
	var ug = qa.gc.dd(tg, sg);
	parentNode.re.push(ug);
	var wf = nf(tg.name + "." + ug.name, "node sql_column", true);
	wf.re = ug;
	parentNode.childNodes[1].appendChild(wf);
	if (!parentNode.className || parentNode.className == null) parentNode.className = "expanded";
}

function bf(parentNode, ze, md, af, ld) {
	var ad = qa.gc.hd();
	ad.jd = ze;
	ad.md = md;
	ad.kd = af;
	ad.ld = ld;
	parentNode.re.push(ad);
	var label = ad.jd.value + "&nbsp;<b>" + ad.md + "</b>&nbsp;";
	if (ad.kd.jc == qa.pb) {
		label += "<i>(SUBQUERY)</i>";
	} else {
		label += ad.kd.value;
	}
	if (ld != null) label = "<b>" + ld + "</b>&nbsp;" + label;
	var wf = nf(label, "node sql_condition", true);
	wf.re = ad;
	parentNode.childNodes[1].appendChild(wf);
	if (!parentNode.className || parentNode.className == null) parentNode.className = "expanded";
	if (ad.kd.jc == qa.pb) {
		cf(wf, ad.kd);
		wf.className = "expanded";
	}
}

function vg(parentNode, ze, af) {
	var wg = qa.gc.nd();
	wg.pd.jd = ze;
	wg.pd.kd = af;
	parentNode.re.push(wg);
	var array = parentNode.re;
	var ad = jg(ze.fd.name).parentNode.re;
	if (array.indexOf(ad) != -1) array.splice(array.indexOf(ad), 1);
	ad = jg(af.fd.name).parentNode.re;
	if (array.indexOf(ad) != -1) array.splice(array.indexOf(ad), 1);
	return wg;
}

function xg(parentNode, value) {
	var ad = qa.gc.qd(value);
	parentNode.re.push(ad);
	var wf = nf(ad.value, "node sql_group", false);
	wf.re = ad;
	parentNode.childNodes[1].appendChild(wf);
	if (!parentNode.className || parentNode.className == null) parentNode.className = "expanded";
}

function yg(parentNode, value) {
	var ad = qa.gc.rd(value);
	parentNode.re.push(ad);
	var wf = nf(ad.value, "node sql_asc", false);
	wf.re = ad;
	parentNode.childNodes[1].appendChild(wf);
	if (!parentNode.className || parentNode.className == null) parentNode.className = "expanded";
}

function ig(hf) {
	if (!hf) return;
	if (hf.className === "node sql_query") {
		if (hf.innerHTML === "UNION") {
			hf.parentNode.parentNode.parentNode.re.qc = null;
		} else if (hf.innerHTML === "SUBQUERY") {
			var array = hf.parentNode.parentNode.parentNode.re;
			array.splice(array.indexOf(hf.parentNode.re), 1);
		}
	} else if (hf.className === "node sql_column" || hf.className === "node sql_expression") {
		var array = hf.parentNode.parentNode.parentNode.re;
		array.splice(array.indexOf(hf.parentNode.re), 1);
	} else if (hf.className === "node sql_table") {
		var array = hf.parentNode.parentNode.parentNode.re;
		array.splice(array.indexOf(hf.parentNode.re), 1);
	} else if (hf.className === "node sql_condition") {
		var array = hf.parentNode.parentNode.parentNode.re;
		array.splice(array.indexOf(hf.parentNode.re), 1);
	} else if (hf.className === "node sql_group") {
		var array = hf.parentNode.parentNode.parentNode.re;
		array.splice(array.indexOf(hf.parentNode.re), 1);
	} else if (hf.className === "node sql_asc" || hf.className === "node sql_desc") {
		var array = hf.parentNode.parentNode.parentNode.re;
		array.splice(array.indexOf(hf.parentNode.re), 1);
	} else {
		alert("Altro:" + hf.className);
	}
	var zg = we == hf;
	var ah = hf.parentNode.previousSibling;
	var parent = hf.parentNode.parentNode;
	parent.removeChild(hf.parentNode);
	if (parent.childNodes.length == 0) parent.parentNode.className = null;
	if (zg) {
		if (ah) pf(ah.firstChild);
		else pf(parent.parentNode.firstChild);
	}
}

function bh() {
	uf(we.parentNode);
}

function ch() {
	xf(we.parentNode);
}

function dh() {
	var eh = document.getElementById("_l_");
	var fh = eh.childNodes[3].childNodes[1];
	fh.rows[0].cells[0].firstChild.value = "";
	var hb = document.getElementById("_m_");
	hb.innerHTML = "expression.new";
	ie("_l_");
}

function gh() {
	var hh = ih.parentNode.parentNode.parentNode.firstChild.firstChild.innerHTML;
	var jh = ih.innerHTML;
	document.getElementById("_r_").value = hh + "." + jh;
	ie("_p_");
}

function kh() {
	var hb = document.getElementById("_e_");
	var ib = document.getElementById("_g_");
	var jb = document.getElementById("_j_");
	ib.value = "";
	jb.value = "";
	if (ih) {
		var hh = ih.parentNode.parentNode.parentNode.firstChild.firstChild.innerHTML;
		var jh = ih.innerHTML;
		ib.value = hh + "." + jh;
	}
	hb.innerHTML = "condition.new";
	ie("_d_");
}

function lh() {
	var parentNode = mh(we);
	xg(parentNode, we.innerHTML);
}

function nh() {
	var qe = document.getElementById("_ca_");
	var parentNode = qe.firstChild.childNodes[1];
	yg(parentNode, we.innerHTML);
}

function oh() {
	ig(we);
}

function ph() {
	if (we.className === "node sql_asc") {
		we.className = "node sql_desc";
		we.parentNode.re.od = 1;
	} else if (we.className === "node sql_desc") {
		we.className = "node sql_asc";
		we.parentNode.re.od = 0;
	}
}

function qh(rh) {
	var sh = true;
	try {
		if (!rh.type || rh.type == "") sh = false;
	} catch (th) {
		sh = false;
	}
	return sh;
}
var uh = [{
		label: "Enable/Disable Distinct",
		vh: wh
	}
	, {
		label: "Add expression...",
		vh: dh
	}
	, {
		label: "Add condition...",
		vh: kh
	}
	, {
		label: "Add sub query",
		vh: ch
	}
	, {
		label: "Add union query",
		vh: bh
	}
	, {
		label: "Add to Group by clause",
		vh: lh
	}
	, {
		label: "Add to Order by clause",
		vh: nh
	}
	, {
		label: "Edit item...",
		vh: wh
	}
	, {
		label: "ASC/DESC",
		vh: ph
	}
	, {
		label: "Remove item",
		vh: oh
	}
	, {
		label: "Remove all",
		vh: wh
	}
	, {
		label: "Remove all join",
		vh: wh
	}
];
var we;

function pf(hf) {
	if (qh(hf)) hf = pe(hf);
	if (we) we.style.backgroundColor = '';
	var lg = mg(hf);
	if (qf != lg.id) {
		var ag = qa.yb[qf].ag;
		for (var xd = 0; xd < ag.length; xd++) {
			ag[xd][0].style.display = "none";
		}
		var xh = document.getElementById("_ia_");
		var yh = xh.getElementsByTagName("img");
		for (var xd = 0; xd < yh.length; xd++) {
			if (yh[xd].className == "lineH" || yh[xd].className == "lineV") xh.removeChild(yh[xd--]);
		}
		var ag = qa.yb[lg.id].ag;
		for (var xd = 0; xd < ag.length; xd++) {
			ag[xd][0].style.display = "block";
		}
		for (var xd = 0; xd < qa.yb[lg.id].bg.length; xd++) {
			ih = qa.yb[lg.id].bg[xd][0];
			zh = qa.yb[lg.id].bg[xd][1];
			ai(qa.yb[lg.id].cg[xd], true);
			ih = null;
			zh = null;
		}
		qf = lg.id;
	}
	we = hf;
	we.style.backgroundColor = "#CEF6CE";
	ke("_ba_");
	var bi = document.getElementById("_ba_");
	while (bi.firstChild) bi.removeChild(bi.firstChild);
	uh[7].vh = wh;
	var ci = new Array();
	if (hf.className === "node sql_query") {
		ci.push(4);
		if (hf.innerHTML == "ROOTQUERY") ci.push(10);
		else ci.push(9);
	} else if (hf.className === "node sql_select") {
		ci.push(0);
		ci.push(1);
		ci.push(3);
	} else if (hf.className === "node sql_column" || hf.className === "node sql_expression") {
		ci.push(5);
		ci.push(6);
		ci.push(7);
		ci.push(9);
		if (hf.className === "node sql_expression") uh[7].vh = db.ue;
	} else if (hf.className === "node sql_from") {
		ci.push(10);
		ci.push(11);
	} else if (hf.className === "node sql_table") {
		ci.push(7);
	} else if (hf.className === "node sql_search") {
		ci.push(2);
		ci.push(10);
	} else if (hf.className === "node sql_condition") {
		if ((hf.parentNode.re.jd.jc === qa.pb) || (hf.parentNode.re.kd.jc === qa.pb)) {
			ci.push(4);
		}
		ci.push(7);
		ci.push(9);
		uh[7].vh = za.ue;
	} else if (hf.className === "node sql_groupby" || hf.className === "node sql_orderby") {
		ci.push(10);
	} else if (hf.className === "node sql_group") {
		ci.push(9);
	} else if (hf.className === "node sql_asc" || hf.className === "node sql_desc") {
		ci.push(8);
		ci.push(9);
	} else {
		pf(hf.parentNode);
	}
	for (var xd = 0; xd < ci.length; xd++) {
		var jf = document.createElement("div");
		jf.className = "nodeAction";
		jf.innerHTML = uh[ci[xd]].label;
		wa(jf, "click", uh[ci[xd]].vh);
		bi.appendChild(jf);
	}
}

function wh() {
	var di = document.getElementById("_c_");
	di.innerHTML = "Sorry, but this functionality is not available for this version.";
	ie("_c_");
}

function fb(ne) {
	var oe = pe(ne);
	var ei = document.getElementById("_u_");
	var fi = document.getElementById("_v_");
	var gi = document.getElementById("_w_");
	ei.className = (oe === ei ? "tabSelected" : "tabDefault");
	fi.className = (oe === fi ? "tabSelected" : "tabDefault");
	gi.className = (oe === gi ? "tabSelected" : "tabDefault");
	var hi = document.getElementById("_x_");
	var ii = document.getElementById("_la_");
	var ji = document.getElementById("_ma_");
	hi.style.display = (oe.id == "_u_" ? "block" : "none");
	ii.style.display = (oe.id == "_v_" ? "block" : "none");
	ji.style.display = (oe.id == "_w_" ? "block" : "none");
	if (oe === fi) {
		var qe = document.getElementById("_ca_");
		if (qe.firstChild) {
			qa.ra.sa("d", qe.firstChild.re, ki, va);
		}
	}
	if (oe === gi) {
		var qe = document.getElementById("_ca_");
		if (qe.firstChild) {
			var di = document.getElementById("_c_");
			di.innerHTML = "Request sent, please wait...";
			ie("_c_");
			qa.ra.sa("f", qe.firstChild.re, li, va);
		}
	}
}

function va(ec) {
	xa();
	var di = document.getElementById("_b_");
	di.innerHTML = ec.fc;
	ie("_b_");
}

function li(ec) {
	var mi = document.getElementById("_ma_");
	for (var xd = 0; xd < mi.childNodes.length; xd++) {
		if (mi.childNodes[xd].id != "_na_") mi.removeChild(mi.childNodes[xd]);
	}
	var ed = document.createElement("table");
	ed.setAttribute("cellPadding", "0");
	ed.setAttribute("cellSpacing", "0");
	ed.style.cssText = "background:#fff;";
	var ni = ed.insertRow(-1);
	ni.className = "result-header";
	var oi = ni.insertCell(0);
	oi.className = "result-column";
	oi.innerHTML = "#";
	for (var xd = 0; xd < ec.pi.length; xd++) {
		oi = ni.insertCell(xd + 1);
		oi.className = "result-column";
		oi.innerHTML = ec.pi[xd].name;
	}
	for (var xd = 0; xd < ec.qi.length; xd++) {
		ni = ed.insertRow(-1);
		var oi = ni.insertCell(0);
		oi.className = "result-column";
		oi.innerHTML = xd + 1;
		for (var qg = 0; qg < ec.qi[xd].length; qg++) {
			oi = ni.insertCell(qg + 1);
			oi.className = "result-value field-type-" + ec.pi[qg].type;
			oi.innerHTML = ec.qi[xd][qg];
		}
	}
	mi.appendChild(ed);
	xa();
}

function ki(ec) {
	var mi = document.getElementById("_la_");
	mi.innerHTML = ec.ri;
}

function ua(ec) {
	var si = document.getElementById("_ha_");
	for (var xd = 0; xd < ec.ti.length; xd++) {
		var ui = document.createElement("div");
		ui.className = "tableItem";
		ui.onclick = vi;
		ui.innerHTML = ec.ti[xd].name;
		si.appendChild(ui);
	}
	var wi = document.createElement("div");
	wi.style.cssText = "clear:both;";
	si.appendChild(wi);
}

function xi(ec) {
	var ed = document.createElement("table");
	ed.setAttribute("cellPadding", "0");
	ed.setAttribute("cellSpacing", "0");
	ed.style.cssText = "background:#fff;";
	var yi = ed.insertRow(-1);
	yi.className = "entityHeader";
	yi.setAttribute("height", "18px");
	var zi = yi.insertCell(0);
	zi.innerHTML = ec.eg;
	zi.className = "entityCaption";
	zi.setAttribute("colSpan", "2");
	zi = yi.insertCell(1);
	zi.innerHTML = '<img src="res/bullet_delete.png">';
	zi.onclick = aj;
	zi.setAttribute("height", "12px");
	zi.setAttribute("width", "12px");
	zi.setAttribute("align", "center");
	zi.setAttribute("valign", "middle");
	zi.style.cssText = "cursor:pointer; border-bottom:1px solid #CCCCCC;";
	var bj = document.createElement("input");
	bj.type = "checkbox";
	yi = ed.insertRow(-1);
	zi = yi.insertCell(0);
	zi.appendChild(bj);
	zi.setAttribute("width", "18px");
	zi = yi.insertCell(1);
	zi.innerHTML = "<b>*</b>";
	for (var xd = 0; xd < ec.pi.length; xd++) {
		var name = ec.pi[xd].name;
		var type = ec.pi[xd].type;
		bj = document.createElement("input");
		bj.type = "checkbox";
		wa(bj, "change", cj);
		yi = ed.insertRow(-1);
		zi = yi.insertCell(0);
		zi.appendChild(bj);
		zi = yi.insertCell(1);
		zi.innerHTML = "<span class='entityField'>" + name + "</span>";
	}
	var dj = document.createElement("div");
	dj.className = "entity";
	dj.style.cssText = "position:absolute; top:47px; left:10px; border:1px solid #CCCCCC; background-color:#DDD";
	dj.appendChild(ed);
	var xh = document.getElementById("_ia_");
	xh.appendChild(dj);
	var lg = mg(we);
	var ej = og(we);
	var ad = dg(ej, null, ec.eg);
	qa.yb[lg.id].ag.push(new Array(dj, ad));
}

function mg(hf) {
	if (!hf || hf == null) {
		var qe = document.getElementById("_ca_");
		hf = qe.firstChild.firstChild.firstChild;
	}
	if (hf.className == "node sql_query") {
		return hf.parentNode;
	} else if (hf.firstChild && hf.firstChild.className == "node sql_query") {
		return hf;
	} else if (hf.className == "node sql_condition") {
		if (hf.parentNode.re.kd.jc == qa.pb) {
			return hf.parentNode;
		}
	} else if (hf.firstChild && hf.firstChild.className == "node sql_condition") {
		if (hf.re.kd.jc == qa.pb) {
			return hf;
		}
	}
	return mg(hf.parentNode);
}

function ff(hf) {
	var fj = mg(hf);
	return fj.childNodes[1].childNodes[0];
}

function og(hf) {
	var fj = mg(hf);
	return fj.childNodes[1].childNodes[1];
}

function gj(hf) {
	var fj = mg(hf);
	return fj.childNodes[1].childNodes[2];
}

function mh(hf) {
	var fj = mg(hf);
	return fj.childNodes[1].childNodes[3];
}

function hj(hf) {
	var fj = mg(hf);
	return fj.childNodes[1].childNodes[4];
}

function vi(ne) {
	ne = ne || window.event;
	var oe = ne.srcElement ? ne.srcElement : ne.target;
	var lg = mg(we);
	var xh = document.getElementById("_ia_");
	for (var xd = 0; xd < qa.yb[lg.id].ag.length; xd++) {
		if (qa.yb[lg.id].ag[xd][1].name == oe.innerHTML) {
			alert("Table already added!");
			return;
		}
	}
	qa.ra.sa("c", {
		ta: null,
		eg: oe.innerHTML
	}, xi, va);
}

function ij(jj, kj) {
	var parentNode = ff(we);
	rg(parentNode, null, jj, kj);
}

function cj(ne) {
	var bj = pe(ne);
	var jj = bj.parentNode.parentNode.parentNode.firstChild.firstChild.innerHTML;
	var kj = bj.parentNode.nextSibling.firstChild.innerHTML;
	if (bj.checked) ij(jj, kj)
	else ig(lj(jj, kj));
}

function lj(jj, kj) {
	var gg = ff(we);
	for (var xd = 0; xd < gg.childNodes[1].childNodes.length; xd++) {
		var hg = gg.childNodes[1].childNodes[xd].re;
		if (hg.jc == qa.sb) {
			if (hg.fd.name == jj && hg.name == kj) {
				return gg.childNodes[1].childNodes[xd].firstChild;
			}
		}
	}
}

function jg(jj) {
	var ng = og(we);
	for (var xd = 0; xd < ng.childNodes[1].childNodes.length; xd++) {
		var hg = ng.childNodes[1].childNodes[xd].re;
		if (hg.jc == qa.rb) {
			if (hg.name == jj) {
				return ng.childNodes[1].childNodes[xd].firstChild;
			}
		}
	}
}

function aj(ne) {
	var dj = pe(ne);
	while (dj && dj.className != "entity") {
		dj = dj.parentNode;
	}
	if (dj) {
		mj(dj);
	}
}

function mj(dj) {
	var lg = mg(we);
	var xh = document.getElementById("_ia_");
	for (var xd = 0; xd < qa.yb[lg.id].bg.length; xd++) {
		ih = qa.yb[lg.id].bg[xd][0];
		zh = qa.yb[lg.id].bg[xd][1];
		var nj = ih.parentNode.parentNode.parentNode.parentNode.parentNode;
		var oj = zh.parentNode.parentNode.parentNode.parentNode.parentNode;
		if (nj == dj || oj == dj) {
			pj(qa.yb[lg.id].cg[xd--]);
		} else {
			ai(qa.yb[lg.id].cg[xd], true);
		}
	}
	for (var xd = 0; xd < qa.yb[lg.id].ag.length; xd++) {
		if (qa.yb[lg.id].ag[xd][0] == dj) {
			qa.yb[lg.id].ag.splice(xd, 1);
			xh.removeChild(dj);
			var qj = dj.childNodes[0].firstChild.firstChild.firstChild.innerHTML;
			var ej = og(we);
			fg(ej, null, qj);
			break;
		}
	}
}

function ai(name, rj) {
	if (ih != zh) {
		var nj = ih.parentNode.parentNode.parentNode.parentNode.parentNode;
		var sj = ih.parentNode.parentNode;
		var oj = zh.parentNode.parentNode.parentNode.parentNode.parentNode;
		var tj = zh.parentNode.parentNode;
		var uj = (nj.offsetTop + sj.offsetTop) + (sj.offsetHeight / 2) + 2;
		var vj = (oj.offsetTop + tj.offsetTop) + (tj.offsetHeight / 2) + 2;
		var wj = nj.offsetLeft;
		var xj = oj.offsetLeft;
		var yj = 0;
		if ((wj + nj.offsetWidth) < xj) {
			wj += nj.offsetWidth;
			yj = (xj - wj) / 2;
			zj(name, uj, wj, yj);
			zj(name, vj, xj - yj, yj);
			if (uj > vj) {
				ak(name, vj, wj + yj, uj - vj);
			} else {
				ak(name, uj, wj + yj, vj - uj);
			}
		} else if ((xj + oj.offsetWidth) < wj) {
			xj += oj.offsetWidth;
			yj = (wj - xj) / 2;
			zj(name, vj, xj, yj);
			zj(name, uj, wj - yj, yj);
			if (uj > vj) {
				ak(name, vj, xj + yj, uj - vj);
			} else {
				ak(name, uj, xj + yj, vj - uj);
			}
		} else {
			wj += nj.offsetWidth;
			xj += oj.offsetWidth;
			yj = (wj > xj ? wj : xj) + 10;
			zj(name, uj, wj, yj - wj);
			zj(name, vj, xj, yj - xj);
			if (uj > vj) {
				ak(name, vj, yj, uj - vj);
			} else {
				ak(name, uj, yj, vj - uj);
			}
		}
		if (!rj) {
			var lg = mg(we);
			var bk = nj.firstChild.firstChild.firstChild.firstChild.innerHTML;
			var ck = sj.childNodes[1].firstChild.innerHTML;
			var dk = oj.firstChild.firstChild.firstChild.firstChild.innerHTML;
			var ek = tj.childNodes[1].firstChild.innerHTML;
			var fk = qa.gc.dd(qa.gc.zc(null, bk), ck);
			var gk = qa.gc.dd(qa.gc.zc(null, dk), ek);
			for (var xd = 0; xd < qa.yb[lg.id].ag.length; xd++) {
				if (qa.yb[lg.id].ag[xd][0] == nj) {
					fk.fd = qa.yb[lg.id].ag[xd][1];
				}
				if (qa.yb[lg.id].ag[xd][0] == oj) {
					gk.fd = qa.yb[lg.id].ag[xd][1];
				}
			}
			var wg = vg(og(we), fk, gk);
			qa.yb[lg.id].cg.push(name);
			qa.yb[lg.id].bg.push(new Array(ih, zh, wg));
		}
	}
	ih = null;
	zh = null;
}

function pj(name) {
	var xh = document.getElementById("_ia_");
	var hk = ce("img", name);
	for (var xd = 0; xd < hk.length; xd++) {
		xh.removeChild(hk[xd]);
	}
	var lg = mg(we);
	var ik = indexOf(qa.yb[lg.id].cg, name);
	if (ik != -1) {
		var wg = qa.yb[lg.id].bg[ik][2];
		kg(wg);
		qa.yb[lg.id].cg.splice(ik, 1);
		qa.yb[lg.id].bg.splice(ik, 1);
	}
}

function jk(ne) {
	ne = ne || window.event;
	var ee = ne.srcElement ? ne.srcElement : ne.target;
	var name = ee.name;
	var hk = ce("img", ee.name);
	for (var xd = 0; xd < hk.length; xd++) {
		if (hk[xd].className == "lineH") {
			hk[xd].setAttribute("src", "res/lineH2.jpg");
		} else {
			hk[xd].setAttribute("src", "res/lineV2.jpg");
		}
	}
	if (confirm("Do you want to remove this relation?")) {
		pj(name);
	} else {
		for (var xd = 0; xd < hk.length; xd++) {
			if (hk[xd].className == "lineH") {
				hk[xd].setAttribute("src", "res/lineH.jpg");
			} else {
				hk[xd].setAttribute("src", "res/lineV.jpg");
			}
		}
	}
}

function zj(name, top, left, width) {
	var kk = document.createElement("img");
	kk.onclick = jk;
	kk.name = name;
	kk.className = "lineH";
	kk.setAttribute("src", "res/lineH.jpg");
	kk.style.cssText = "cursor:pointer; position:absolute; top:" + top + "px; left:" + left + "px; width:" + width + "px; height:1px; z-index:1;";
	var lk = document.getElementById("_ia_");
	lk.appendChild(kk);
}

function ak(name, top, left, height) {
	var kk = document.createElement("img");
	kk.onclick = jk;
	kk.name = name;
	kk.className = "lineV";
	kk.setAttribute("src", "res/lineV.jpg");
	kk.style.cssText = "cursor:pointer; position:absolute; top:" + top + "px; left:" + left + "px; width:1px; height:" + height + "px; z-index:1;";
	var lk = document.getElementById("_ia_");
	lk.appendChild(kk);
}
if (!document.all) document.captureEvents(Event.MOUSEMOVE);
document.onmousedown = mk;
document.onmouseup = nk;
var ok = false;
var pk = null;
var ih = null;
var zh = null;
var qk = 0;
var rk = 0;

function sk(ne) {
	if (!ok) return;
	var ne = window.event || ne;
	var tk = document.getElementById("log");
	var uk = document.getElementById("split");
	var uk = document.getElementById("_x_");
	var left = document.getElementById("_y_");
	var vk = document.getElementById("_da_");
	var right = document.getElementById("_ea_");
	var yj = ne.clientX;
	vk.style.left = (yj - 4) + 'px';
	left.style.width = (yj - 4) + 'px';
	right.style.left = (vk.offsetWidth + vk.offsetLeft) + 'px';
	right.style.cssText = "position:absolute; overflow:hidden; background-color:#FFFFFF; top:0px; left:" + (vk.offsetWidth + vk.offsetLeft) + "px !important; left:" + (vk.offsetWidth + vk.offsetLeft) + "px; width:" + (uk.offsetWidth - (vk.offsetWidth + vk.offsetLeft)) + "px; height:100%;";
	return false;
}

function wk(ne) {
	if (!ok) return;
	var ne = window.event || ne;
	var xk = ne.clientY - qk;
	var yk = ne.clientX - rk;
	pk.style.top = xk;
	pk.style.left = yk;
	return false;
}

function zk(ne) {
	if (!ok) return;
	var ne = window.event || ne;
	var xk = ne.clientY - qk;
	var yk = ne.clientX - rk;
	pk.style.top = xk;
	pk.style.left = yk;
	return false;
}

function mk(ne) {
	ne = ne || window.event;
	var al = ne.srcElement ? ne.srcElement : ne.target;
	if (al.id == "_da_") {
		ok = true;
		document.onmousemove = sk;
		return false;
	} else if (al.className == "entityCaption") {
		pk = al.parentNode.parentNode.parentNode.parentNode;
		pk.style.zIndex = 100;
		qk = ne.clientY - pk.offsetTop;
		rk = ne.clientX - pk.offsetLeft;
		ok = true;
		document.onmousemove = wk;
		return false;
	} else if (al.className == "entityField") {
		ih = al;
		var bl = al.parentNode;
		var cl = bl.parentNode;
		var dj = cl.parentNode.parentNode.parentNode;
		var dl = dj.offsetTop + cl.offsetTop + al.offsetTop;
		var el = dj.offsetLeft + bl.offsetLeft + al.offsetLeft;
		qk = ne.clientY - dl;
		rk = ne.clientX - el;
		pk = document.createElement("span");
		pk.appendChild(document.createTextNode(al.innerHTML));
		pk.style.cssText = "cursor:pointer;position:absolute;top:" + dl + "px;left:" + el + "px;z-index:100;border:1px solid #ccc;color: #ccc";
		var xh = document.getElementById("_ia_");
		xh.appendChild(pk);
		ok = true;
		document.onmousemove = zk;
		return false;
	}
}

function nk(ne) {
	ne = ne || window.event;
	if (pk) {
		document.onmousemove = null;
		var lg = mg(we);
		if (pk.className == "") {
			var right = document.getElementById("_ea_");
			var xh = document.getElementById("_ia_");
			xh.removeChild(pk);
			var fl = false;
			for (var xd = 0; xd < qa.yb[lg.id].ag.length; xd++) {
				var gl = qa.yb[lg.id].ag[xd][0].offsetLeft + xh.offsetLeft + right.offsetLeft;
				var hl = qa.yb[lg.id].ag[xd][0].offsetTop + xh.offsetTop + 32;
				var il = gl + qa.yb[lg.id].ag[xd][0].offsetWidth;
				var jl = hl + qa.yb[lg.id].ag[xd][0].offsetHeight;
				if (gl < ne.clientX && il > ne.clientX && hl < ne.clientY && jl > ne.clientY) {
					var ni = qa.yb[lg.id].ag[xd][0].firstChild.firstChild.childNodes[2];
					while (ni) {
						var kl = gl + ni.offsetLeft;
						var ll = hl + ni.offsetTop;
						var ml = kl + ni.offsetWidth;
						var nl = ll + ni.offsetHeight;
						if (kl < ne.clientX && ml > ne.clientX && ll < ne.clientY && nl > ne.clientY) {
							zh = ni.firstChild.nextSibling.firstChild;
							ai(new Date().getTime(), false);
							break;
						}
						ni = ni.nextSibling;
					}
					fl = true;
					break;
				}
			}
			if (!fl) {
				var ol = document.getElementById("_ka_");
				var gl = ol.offsetLeft + xh.offsetLeft + right.offsetLeft;
				var hl = ol.offsetTop + xh.offsetTop + 32;
				var il = gl + ol.offsetWidth;
				var jl = hl + ol.offsetHeight;
				if (gl < ne.clientX && il > ne.clientX && hl < ne.clientY && jl > ne.clientY) {
					gh();
				} else {
					ol = document.getElementById("_ja_");
					gl = ol.offsetLeft + xh.offsetLeft + right.offsetLeft;
					hl = ol.offsetTop + xh.offsetTop + 32;
					il = gl + ol.offsetWidth;
					jl = hl + ol.offsetHeight;
					if (gl < ne.clientX && il > ne.clientX && hl < ne.clientY && jl > ne.clientY) {
						var ye = gj(we);
						pf(ye.firstChild);
						kh();
					}
				}
			}
		} else if (pk.className == "entity") {
			var xh = document.getElementById("_ia_");
			var yh = xh.getElementsByTagName("img");
			for (var xd = 0; xd < yh.length; xd++) {
				if (yh[xd].className == "lineH" || yh[xd].className == "lineV") xh.removeChild(yh[xd--]);
			}
			for (var xd = 0; xd < qa.yb[lg.id].bg.length; xd++) {
				ih = qa.yb[lg.id].bg[xd][0];
				zh = qa.yb[lg.id].bg[xd][1];
				ai(qa.yb[lg.id].cg[xd], true);
			}
		}
		if (pk.style.zIndex == 100) {
			pk.style.zIndex = 10;
		}
	}
	ih = null;
	zh = null;
	pk = null;
	ok = false;
	document.onmousemove = null;
}