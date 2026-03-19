// Sandenbanden — Shared App Logic
// Theme-specific config is set via window.THEME before this script loads

// SECTION TOGGLE
document.addEventListener('click',function(e){
  const header=e.target.closest('[data-toggle="section"]');
  if(!header)return;
  const body=header.nextElementSibling;
  if(!body)return;
  const toggle=header.querySelector('.side-section-toggle');
  body.classList.toggle('open');
  if(toggle)toggle.classList.toggle('open');
});

// --- FIREBASE ---
firebase.initializeApp({
  apiKey:"AIzaSyDN-lWEsjUYVlN6jO9fEeaxKzgLo2PI5Po",
  authDomain:"treningskonkurranse.firebaseapp.com",
  databaseURL:"https://treningskonkurranse-default-rtdb.europe-west1.firebasedatabase.app",
  projectId:"treningskonkurranse",
  storageBucket:"treningskonkurranse.firebasestorage.app",
  messagingSenderId:"667059906306",
  appId:"1:667059906306:web:180229979589558782841b"
});
const db = firebase.database();

// --- ACTIVITIES ---
const DEFAULT_ACTIVITIES = [
  {key:'run',label:'Løping',unit:'km',pts:10},{key:'bike',label:'Sykling',unit:'km',pts:3},
  {key:'swim',label:'Svømming',unit:'km',pts:20},{key:'push',label:'Pushups',unit:'rep',pts:0.5},
  {key:'pull',label:'Pullups',unit:'rep',pts:1.5},{key:'sit',label:'Situps',unit:'rep',pts:0.3},
  {key:'weight',label:'Vekttrening',unit:'min',pts:0.8},{key:'plank',label:'Planke',unit:'min',pts:5},
  {key:'healthy',label:'Sunn dag',unit:'stk',pts:10},
];
let activities = DEFAULT_ACTIVITIES.map(a=>({...a}));
function saveActivities(a){db.ref('activities').set(a);}
function pts(e){return activities.reduce((s,a)=>s+(e[a.key]||0)*a.pts,0);}

// PALETTE — read from theme or use defaults
const PALETTE = (window.THEME && window.THEME.palette) || [
  {color:'#4A9EFF',bg:'rgba(74,158,255,.12)'},{color:'#FF6B5A',bg:'rgba(255,107,90,.12)'},
  {color:'#36D399',bg:'rgba(54,211,153,.12)'},{color:'#FFB830',bg:'rgba(255,184,48,.12)'},
  {color:'#A855F7',bg:'rgba(168,85,247,.12)'},{color:'#FF5E6C',bg:'rgba(255,94,108,.12)'},
  {color:'#34D399',bg:'rgba(52,211,153,.12)'},{color:'#FB923C',bg:'rgba(251,146,60,.12)'},
];
function pal(i){return PALETTE[i%PALETTE.length];}

// --- PLAYERS ---
const DEFAULT_PLAYERS = [{id:'p1',name:'Viktor'},{id:'p2',name:'Sølve'}];
let players = DEFAULT_PLAYERS.map(p=>({...p}));
function savePlayers(p){db.ref('players').set(p);}
function pName(id){return(players.find(p=>p.id===id)||{}).name||id;}
function pIdx(id){return players.findIndex(p=>p.id===id);}

// --- DATA ---
let data=[],charts={},idCounter=Date.now(),editingId=null;

function getDateRange(){
  if(!data.length)return[];
  const ds=data.map(d=>d.date).sort(),s=new Date(ds[0]),e=new Date(ds[ds.length-1]),r=[];
  for(let d=new Date(s);d<=e;d.setDate(d.getDate()+1))r.push(d.toISOString().slice(0,10));
  return r;
}
function fmtDate(s){return new Date(s+'T00:00:00').toLocaleDateString('nb-NO',{day:'numeric',month:'short'});}
function fmtDateShort(s){return new Date(s+'T00:00:00').toLocaleDateString('nb-NO',{day:'numeric',month:'short'}).replace('.','');}
function getWeek(ds){const d=new Date(ds+'T00:00:00');d.setDate(d.getDate()+3-(d.getDay()+6)%7);const w1=new Date(d.getFullYear(),0,4);return'Uke '+(1+Math.round(((d-w1)/86400000-3+(w1.getDay()+6)%7)/7));}

// Chart colors from theme
const _t = window.THEME || {};
const GC = _t.gridColor || 'rgba(255,255,255,.04)';
const TC = _t.tickColor || 'rgba(255,255,255,.25)';
const _tf = _t.fontFamily || 'Inter';
const _ttBg = _t.tooltipBg || 'rgba(17,20,37,.95)';
const _ttBorder = _t.tooltipBorder || 'rgba(255,255,255,.08)';
const _ttTitle = _t.tooltipTitle || 'rgba(255,255,255,.6)';
const _ttBody = _t.tooltipBody || '#fff';

function mkChart(id,cfg){if(charts[id])charts[id].destroy();const el=document.getElementById(id);if(el)charts[id]=new Chart(el,cfg);}
const baseOpts={responsive:true,maintainAspectRatio:false,interaction:{mode:'index',intersect:false},plugins:{legend:{display:false},tooltip:{backgroundColor:_ttBg,borderColor:_ttBorder,borderWidth:1,titleColor:_ttTitle,bodyColor:_ttBody,padding:10,cornerRadius:8,titleFont:{size:11,family:_tf},bodyFont:{size:12,family:_tf,weight:600}}},scales:{x:{grid:{color:GC,drawTicks:false},ticks:{color:TC,font:{size:10,family:_tf},maxRotation:0,autoSkip:true,padding:6},border:{display:false}},y:{grid:{color:GC,drawTicks:false},ticks:{color:TC,font:{size:10},padding:8},border:{display:false},beginAtZero:true}}};
function deepMerge(a,b){const r={...a};for(const k in b){if(b[k]&&typeof b[k]==='object'&&!Array.isArray(b[k]))r[k]=deepMerge(a[k]||{},b[k]);else r[k]=b[k];}return r;}

function buildLegend(id){document.getElementById(id).innerHTML=players.map((p,i)=>`<span class="chart-leg"><span class="chart-leg-dot" style="background:${pal(i).color}"></span>${p.name}</span>`).join('');}
function buildDS(dpp,type){return players.map((p,i)=>({label:p.name,data:dpp[p.id]||[],borderColor:pal(i).color,backgroundColor:pal(i).bg,borderWidth:2.5,pointRadius:3,pointHoverRadius:6,pointBackgroundColor:pal(i).color,pointBorderColor:pal(i).color,tension:.35,fill:true,...(type==='act'?{spanGaps:false}:{})}));}

// HEADER STATS
function updateStats(){
  const totals=players.map(p=>({name:p.name,total:Math.round(data.filter(e=>e.p===p.id).reduce((a,e)=>a+pts(e),0))})).sort((a,b)=>b.total-a.total);
  document.getElementById('statLeader').textContent=totals[0]?.name||'—';
  document.getElementById('statTotal').textContent=Math.round(data.reduce((a,e)=>a+pts(e),0)).toLocaleString('nb-NO');
  document.getElementById('statWorkouts').textContent=data.length;
}

// LEAGUE
function renderLeague(){
  const totals=players.map((p,i)=>({...p,idx:i,total:Math.round(data.filter(e=>e.p===p.id).reduce((a,e)=>a+pts(e),0)),count:data.filter(e=>e.p===p.id).length})).sort((a,b)=>b.total-a.total);
  document.getElementById('leagueTable').innerHTML=totals.map((t,rank)=>{
    const c=pal(t.idx).color;
    const avg=t.count?Math.round(t.total/t.count*10)/10:0;
    return`<div class="liga-card${rank===0?' first':''}">
      <div class="liga-rank r${rank+1}">${rank+1}</div>
      <div class="liga-stripe" style="background:${c}"></div>
      <div class="liga-info">
        <div class="liga-name">${t.name}</div>
        <div class="liga-stats">${t.total} p · ${t.count} økter · Ø ${avg}</div>
      </div>
      <div class="liga-trophy">🏅</div>
    </div>`;
  }).join('');
}

// CHARTS
function renderCumul(){const days=getDateRange();if(!days.length)return;const pp={};players.forEach(p=>{let c=0;pp[p.id]=days.map(day=>{data.filter(e=>e.date===day&&e.p===p.id).forEach(e=>c+=pts(e));return Math.round(c*10)/10;});});buildLegend('legendCumul');mkChart('cumulChart',{type:'line',data:{labels:days.map(d=>fmtDateShort(d)),datasets:buildDS(pp,'cumul')},options:deepMerge(baseOpts,{plugins:{tooltip:{callbacks:{label:c=>`  ${c.dataset.label} : ${c.parsed.y}`}}}})});}
function renderDaily(){const days=getDateRange();if(!days.length)return;const pp={};players.forEach(p=>{pp[p.id]=days.map(d=>Math.round(data.filter(e=>e.date===d&&e.p===p.id).reduce((a,e)=>a+pts(e),0)*10)/10);});buildLegend('legendDaily');mkChart('dailyChart',{type:'line',data:{labels:days.map(d=>fmtDateShort(d)),datasets:buildDS(pp,'daily')},options:deepMerge(baseOpts,{plugins:{tooltip:{callbacks:{label:c=>c.parsed.y>0?`  ${c.dataset.label}: ${c.parsed.y}p`:null}}}})});}
let currentAct='run';
function renderActPills(){const c=document.getElementById('actPills');c.innerHTML='';activities.forEach(a=>{const el=document.createElement('button');el.className='act-pill'+(a.key===currentAct?' on':'');el.textContent=a.label;el.onclick=()=>{currentAct=a.key;renderActPills();renderActChart();};c.appendChild(el);});}
function renderActChart(){const days=getDateRange();if(!days.length)return;const def=activities.find(a=>a.key===currentAct);if(!def)return;const pp={};players.forEach(p=>{pp[p.id]=days.map(d=>{const v=data.filter(e=>e.date===d&&e.p===p.id).reduce((a,e)=>a+(e[def.key]||0),0);return v||null;});});buildLegend('legendAct');mkChart('actChart',{type:'line',data:{labels:days.map(d=>fmtDateShort(d)),datasets:buildDS(pp,'act')},options:deepMerge(baseOpts,{plugins:{tooltip:{callbacks:{label:c=>c.parsed.y===null?null:`  ${c.dataset.label}: ${c.parsed.y} ${def.unit}`}}}})});}
function renderWeek(){const weeks={};data.forEach(e=>{const w=getWeek(e.date);if(!weeks[w]){weeks[w]={};players.forEach(p=>weeks[w][p.id]=0);}if(!weeks[w][e.p])weeks[w][e.p]=0;weeks[w][e.p]+=pts(e);});const wl=Object.keys(weeks).sort(),pp={};players.forEach(p=>{pp[p.id]=wl.map(w=>Math.round(weeks[w][p.id]||0));});mkChart('weekChart',{type:'line',data:{labels:wl,datasets:buildDS(pp,'week')},options:deepMerge(baseOpts,{scales:{x:{ticks:{autoSkip:false,maxRotation:0}}}})});const wrap=document.getElementById('weekRows');wrap.innerHTML='';const mx=Math.max(...wl.flatMap(w=>players.map(p=>weeks[w][p.id]||0)),1);wl.forEach(w=>{const row=document.createElement('div');row.className='week-row';row.innerHTML=`<div class="week-label">${w}</div><div class="week-bar-wrap">${players.map((p,i)=>{const v=Math.round(weeks[w][p.id]||0);return`<div class="week-bar-row"><div class="week-bar-bg"><div class="week-bar-fill" style="width:${v/mx*100}%;background:${pal(i).color}"></div></div><div class="week-bar-val" style="color:${pal(i).color}">${v}p</div></div>`;}).join('')}</div>`;wrap.appendChild(row);});}

// ACTIVITY FIELDS
function buildForm(){
  document.getElementById('fp').innerHTML=players.map(p=>`<option value="${p.id}">${p.name}</option>`).join('');
  document.getElementById('actFields').innerHTML=activities.map(a=>
    `<div class="act-row"><div class="act-label">${a.label}</div><input class="act-input" type="number" id="f_${a.key}" placeholder="0" min="0" step="0.1" oninput="updatePreview()"><div class="act-unit">${a.unit}</div></div>`
  ).join('');
}
window.updatePreview=function(){
  const obj={};activities.forEach(a=>{obj[a.key]=parseFloat(document.getElementById('f_'+a.key)?.value)||0;});
  const p=pts(obj);
  document.getElementById('fpreview').textContent=p>0?Math.round(p*10)/10:'0.0';
};

// SUBMIT
window.submitForm=function(){
  const date=document.getElementById('fd').value;if(!date){alert('Velg en dato');return;}
  const pid=document.getElementById('fp').value;
  const entry={p:pid,date,name:pName(pid)+' økt'};
  activities.forEach(a=>{const v=parseFloat(document.getElementById('f_'+a.key)?.value)||0;if(v)entry[a.key]=v;});
  if(editingId){entry.id=editingId;db.ref('entries/'+editingId).set(entry);editingId=null;document.getElementById('formTitle').textContent='Logg økt';document.getElementById('formSubmitBtn').textContent='Logg økt';}
  else{entry.id='u'+(++idCounter).toString(36);db.ref('entries/'+entry.id).set(entry);}
  activities.forEach(a=>{const el=document.getElementById('f_'+a.key);if(el)el.value='';});
  document.getElementById('fpreview').textContent='0.0';
};
window.editEntry=function(id){
  const entry=data.find(e=>e.id===id);if(!entry)return;
  editingId=id;
  document.getElementById('formTitle').textContent='Rediger økt';
  document.getElementById('formSubmitBtn').textContent='Lagre endring';
  document.getElementById('fp').value=entry.p;
  document.getElementById('fd').value=entry.date;
  activities.forEach(a=>{const el=document.getElementById('f_'+a.key);if(el)el.value=entry[a.key]||'';});
  updatePreview();
  const body=document.getElementById('logSection');body.classList.add('open');
  body.previousElementSibling.querySelector('.side-section-toggle').classList.add('open');
};
window.deleteEntry=function(id){db.ref('entries/'+id).remove();};

// HISTORIKK
function getEntryActivities(e){
  return activities.filter(a=>(e[a.key]||0)>0).map(a=>a.label).join(', ')||'Økt';
}
function renderRecent(){
  const sorted=[...data].sort((a,b)=>b.date.localeCompare(a.date)).slice(0,20);
  document.getElementById('histCount').textContent=data.length;
  document.getElementById('recentLog').innerHTML=sorted.map(e=>{
    const p=Math.round(pts(e)*10)/10,pi=pIdx(e.p),c=pal(pi>=0?pi:0).color;
    return`<div class="hist-card">
      <div class="hist-card-actions"><button class="hist-btn" onclick="editEntry('${e.id}')">✎</button><button class="hist-btn del" onclick="deleteEntry('${e.id}')">🗑</button></div>
      <div class="hist-card-header"><div class="hist-dot" style="background:${c}"></div><div class="hist-card-name" style="color:${c}">${pName(e.p)}</div></div>
      <div class="hist-card-date">${fmtDateShort(e.date)}</div>
      <div class="hist-card-acts">${getEntryActivities(e)}</div>
      <div class="hist-card-pts" style="color:${c}"><span class="hist-card-pts-icon">✦</span> ${p} p</div>
    </div>`;
  }).join('');
}

// ACT CONFIG
function renderActConfig(){
  document.getElementById('actConfigList').innerHTML=activities.map((a,i)=>
    `<div class="pts-card">
      <div class="pts-card-info"><div class="pts-card-name">${a.label}</div><div class="pts-card-unit">${a.unit}</div></div>
      <input class="pts-input" type="number" value="${a.pts}" step="0.1" min="0" onchange="updateActPts(${i},this.value)">
      <span class="pts-p">p</span>
      ${activities.length>3?`<button class="pts-del" onclick="removeActivity(${i})">🗑</button>`:''}
    </div>`
  ).join('');
}
window.updateActPts=function(i,val){activities[i].pts=parseFloat(val)||0;saveActivities(activities);};
window.removeActivity=function(i){if(activities.length<=3)return;activities.splice(i,1);saveActivities(activities);};
window.addActivity=function(){
  const nameEl=document.getElementById('newActName'),unitEl=document.getElementById('newActUnit'),ptsEl=document.getElementById('newActPts');
  const label=nameEl.value.trim();if(!label)return;
  activities.push({key:label.toLowerCase().replace(/[^a-z0-9]/g,'')+'_'+Date.now().toString(36),label,unit:unitEl.value,pts:parseFloat(ptsEl.value)||1});
  saveActivities(activities);nameEl.value='';ptsEl.value='1';
};

// PLAYERS
function renderPlayerList(){
  document.getElementById('playerList').innerHTML=players.map((p,i)=>{
    return`<div class="player-card"><div class="player-color-block" style="background:${pal(i).color}"></div><div class="player-name">${p.name}</div>${players.length>2?`<button class="player-del" onclick="removePlayer('${p.id}')">🗑</button>`:''}</div>`;
  }).join('');
  const preview=document.getElementById('newPlayerColorPreview');
  if(preview) preview.style.background=pal(players.length).color;
}
window.addPlayer=function(){const input=document.getElementById('newPlayerName'),name=input.value.trim();if(!name)return;players.push({id:'p'+Date.now().toString(36),name});savePlayers(players);input.value='';};
window.removePlayer=function(id){if(players.length<=2)return;players=players.filter(p=>p.id!==id);savePlayers(players);data.filter(e=>e.p===id).forEach(e=>db.ref('entries/'+e.id).remove());};

// TABS
window.goChart=function(id,btn){
  document.querySelectorAll('.chart-view').forEach(v=>v.classList.remove('active'));
  document.querySelectorAll('.tab-pill').forEach(b=>b.classList.remove('active'));
  document.getElementById('cv-'+id).classList.add('active');
  btn.classList.add('active');
  if(id==='aktivitet'){renderActPills();renderActChart();}
  if(id==='uke')renderWeek();
  if(id==='daglig')renderDaily();
  if(id==='oversikt')renderCumul();
};

function renderAll(){updateStats();renderLeague();renderCumul();renderDaily();renderRecent();renderActConfig();renderPlayerList();
  if(document.getElementById('cv-aktivitet').classList.contains('active')){renderActPills();renderActChart();}
  if(document.getElementById('cv-uke').classList.contains('active'))renderWeek();
}

// --- FIREBASE LISTENERS ---
let initialized=false;
db.ref('entries').on('value',snap=>{const v=snap.val();data=v?Object.values(v).sort((a,b)=>(a.date||'').localeCompare(b.date||'')):[];if(initialized)renderAll();});
db.ref('players').on('value',snap=>{const v=snap.val();if(v)players=Array.isArray(v)?v:Object.values(v);if(initialized){buildForm();renderAll();}});
db.ref('activities').on('value',snap=>{const v=snap.val();if(v)activities=Array.isArray(v)?v:Object.values(v);if(initialized){buildForm();renderAll();}});

// INIT
try { document.getElementById('fd').value=new Date().toISOString().slice(0,10); } catch(e){}
db.ref('entries').once('value').then(()=>{
  initialized=true;
  try { buildForm();renderActPills();renderAll(); } catch(e){ console.error('Init error:',e); }
}).catch(e=>{ console.error('Firebase load error:',e); initialized=true; try{buildForm();renderActPills();renderAll();}catch(e2){} });
