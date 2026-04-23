<?php /* Template Name: Intel Dashboard */ if (!defined("ABSPATH")) exit; ?>
<!DOCTYPE html>

<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<title>SaleFish Competitor Intelligence Dashboard</title>
<link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'%3E%3Crect width='512' height='512' rx='80' fill='%233D2D7C'/%3E%3Ctext x='256' y='360' text-anchor='middle' font-family='Georgia,serif' font-size='300' font-weight='400' fill='white'%3Esf%3C/text%3E%3C/svg%3E">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  :root {
    --bg-primary: #111318;
    --bg-secondary: #161921;
    --bg-card: #1C1F2B;
    --bg-card-hover: #232736;
    --accent: #5B9A6F;
    --accent-light: #7FBF8E;
    --accent-glow: rgba(91,154,111,0.15);
    --text-primary: #E8E8EC;
    --text-secondary: #9A9BA8;
    --text-muted: #5A5B6A;
    --border: rgba(91,154,111,0.25);
    --border-subtle: rgba(255,255,255,0.06);
    --red: #E05555;
    --red-bg: rgba(224,85,85,0.12);
    --amber: #E8A838;
    --amber-bg: rgba(232,168,56,0.12);
    --green: #5B9A6F;
    --green-bg: rgba(91,154,111,0.12);
    --blue: #5B9A6F;
    --blue-bg: rgba(91,154,111,0.12);
    --purple: #5B9A6F;
    --purple-bg: rgba(91,154,111,0.12);
  }
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body {
    font-family: 'Inter', system-ui, sans-serif;
    background: var(--bg-primary);
    color: var(--text-primary);
    min-height: 100vh;
    font-size: 14px;
    line-height: 1.6;
  }
  /* HEADER */
  .header {
    background: linear-gradient(135deg, #111318 0%, #1A2520 50%, #111318 100%);
    border-bottom: 1px solid var(--border);
    padding: 24px 32px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: sticky;
    top: 0;
    z-index: 100;
    backdrop-filter: blur(10px);
  }
  .header-left { display: flex; align-items: center; gap: 16px; }
  .logo-mark {
    width: 44px; height: 44px;
    background: #3D2D7C;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-family: 'Playfair Display', Georgia, serif;
    font-weight: 400; font-size: 22px; color: #fff;
    letter-spacing: -1px;
    box-shadow: 0 4px 20px rgba(61,45,124,0.4);
  }
  .header-logo-text {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 28px; font-weight: 500; color: #9B8EC4;
    letter-spacing: 0.5px; line-height: 1;
  }
  .header-title { font-size: 20px; font-weight: 700; color: var(--text-primary); }
  .header-sub { font-size: 12px; color: var(--text-muted); margin-top: 2px; }
  .header-meta { text-align: right; }
  .header-date { font-size: 12px; color: var(--text-muted); }
  .header-badge {
    display: inline-block; margin-top: 4px;
    background: var(--blue-bg); color: var(--accent-light);
    border: 1px solid rgba(91,154,111,0.3); border-radius: 20px;
    padding: 2px 10px; font-size: 11px; font-weight: 600;
  }
  /* TABS */
  .tab-nav {
    background: var(--bg-secondary);
    border-bottom: 1px solid var(--border-subtle);
    padding: 0 32px;
    display: flex; gap: 0;
    overflow-x: auto;
    scrollbar-width: none;
  }
  .tab-nav::-webkit-scrollbar { display: none; }
  .tab-btn {
    padding: 14px 18px;
    background: none; border: none;
    color: var(--text-muted);
    font-family: 'Inter', sans-serif;
    font-size: 13px; font-weight: 500;
    cursor: pointer; white-space: nowrap;
    border-bottom: 3px solid transparent;
    transition: all 0.2s;
    position: relative; top: 1px;
  }
  .tab-btn:hover { color: var(--text-primary); }
  .tab-btn.active {
    color: var(--accent-light);
    border-bottom-color: var(--accent);
    font-weight: 600;
  }
  /* CONTENT */
  .content { padding: 32px; max-width: 1400px; margin: 0 auto; }
  .tab-panel { display: none; }
  .tab-panel.active { display: block; }
  /* GRID */
  .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
  .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
  .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
  /* CARDS */
  .card {
    background: var(--bg-card);
    border: 1px solid var(--border-subtle);
    border-radius: 12px;
    padding: 20px;
    transition: all 0.2s;
  }
  .card:hover {
    border-color: var(--border);
    background: var(--bg-card-hover);
    transform: translateY(-1px);
  }
  .card-title {
    font-size: 13px; font-weight: 600;
    color: var(--text-secondary); text-transform: uppercase;
    letter-spacing: 0.05em; margin-bottom: 12px;
  }
  /* SECTION HEADERS */
  .section-header { margin-bottom: 24px; }
  .section-title { font-size: 22px; font-weight: 700; color: var(--text-primary); }
  .section-sub { font-size: 14px; color: var(--text-muted); margin-top: 4px; }
  .section-divider { height: 2px; background: linear-gradient(90deg, var(--accent), transparent); margin: 32px 0; border-radius: 2px; }
  /* METRICS */
  .metric-value { font-size: 32px; font-weight: 800; color: var(--text-primary); line-height: 1.1; }
  .metric-label { font-size: 12px; color: var(--text-muted); margin-top: 4px; }
  .metric-change { font-size: 12px; font-weight: 600; margin-top: 6px; }
  .change-up { color: var(--green); }
  .change-down { color: var(--red); }
  /* BADGES */
  .badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 10px; border-radius: 20px;
    font-size: 11px; font-weight: 700; letter-spacing: 0.03em;
  }
  .badge-red { background: var(--red-bg); color: var(--red); border: 1px solid rgba(255,59,59,0.3); }
  .badge-amber { background: var(--amber-bg); color: var(--amber); border: 1px solid rgba(255,176,32,0.3); }
  .badge-green { background: var(--green-bg); color: var(--green); border: 1px solid rgba(0,196,140,0.3); }
  .badge-blue { background: var(--blue-bg); color: #7FBF8E; border: 1px solid rgba(91,154,111,0.3); }
  .badge-purple { background: var(--purple-bg); color: #7FBF8E; border: 1px solid rgba(91,154,111,0.3); }
  /* ALERTS */
  .alert {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 14px 16px; border-radius: 10px;
    margin-bottom: 10px; border-left: 3px solid;
  }
  .alert-red { background: var(--red-bg); border-color: var(--red); }
  .alert-amber { background: var(--amber-bg); border-color: var(--amber); }
  .alert-blue { background: var(--blue-bg); border-color: var(--accent); }
  .alert-icon { font-size: 16px; flex-shrink: 0; margin-top: 1px; }
  .alert-text { font-size: 13px; color: var(--text-primary); }
  .alert-text strong { display: block; font-weight: 600; margin-bottom: 2px; }
  /* COMPETITOR CARDS */
  .comp-card {
    background: var(--bg-card);
    border: 1px solid var(--border-subtle);
    border-radius: 14px;
    padding: 24px;
    transition: all 0.25s;
  }
  .comp-card:hover { border-color: var(--border); transform: translateY(-2px); box-shadow: 0 8px 32px rgba(0,0,0,0.3); }
  .comp-header { display: flex; align-items: flex-start; gap: 16px; margin-bottom: 16px; }
  .comp-logo {
    width: 52px; height: 52px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: 20px; color: #fff; flex-shrink: 0;
  }
  .comp-info { flex: 1; }
  .comp-name { font-size: 17px; font-weight: 700; margin-bottom: 4px; }
  .comp-location { font-size: 12px; color: var(--text-muted); margin-bottom: 6px; }
  .comp-desc { font-size: 13px; color: var(--text-secondary); line-height: 1.6; margin-bottom: 16px; }
  .comp-section-title { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-muted); margin-bottom: 8px; }
  .news-item { font-size: 13px; color: var(--text-secondary); padding: 8px 0; border-bottom: 1px solid var(--border-subtle); }
  .news-item:last-child { border-bottom: none; }
  .news-item::before { content: "→ "; color: var(--accent-light); font-weight: 600; }
  .stat-row { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid var(--border-subtle); }
  .stat-row:last-child { border-bottom: none; }
  .stat-key { font-size: 12px; color: var(--text-muted); }
  .stat-val { font-size: 13px; font-weight: 600; color: var(--text-primary); }
  /* FEATURE MATRIX */
  .matrix-wrap { overflow-x: auto; }
  .matrix-table { width: 100%; border-collapse: collapse; min-width: 900px; }
  .matrix-table th {
    background: var(--bg-secondary); padding: 12px 14px;
    text-align: center; font-size: 12px; font-weight: 700;
    color: var(--text-secondary); letter-spacing: 0.03em;
    border: 1px solid var(--border-subtle); white-space: nowrap;
  }
  .matrix-table th:first-child { text-align: left; width: 200px; }
  .matrix-table td {
    padding: 10px 14px; text-align: center;
    border: 1px solid var(--border-subtle);
    font-size: 13px;
  }
  .matrix-table td:first-child { text-align: left; font-weight: 500; color: var(--text-secondary); }
  .matrix-table tr:hover td { background: var(--bg-card); }
  .th-salefish { color: var(--accent-light) !important; background: rgba(91,154,111,0.1) !important; }
  .check-yes { color: var(--green); font-size: 16px; font-weight: 700; }
  .check-partial { color: var(--amber); font-size: 14px; font-weight: 600; }
  .check-no { color: var(--text-muted); font-size: 14px; }
  /* MARKET STATS */
  .market-stat-card {
    background: linear-gradient(135deg, var(--bg-card), var(--bg-secondary));
    border: 1px solid var(--border);
    border-radius: 14px; padding: 24px;
    text-align: center;
  }
  .market-stat-card .big-num { font-size: 42px; font-weight: 800; color: var(--accent-light); line-height: 1; }
  .market-stat-card .unit { font-size: 18px; font-weight: 600; color: var(--text-muted); }
  .market-stat-card .label { font-size: 13px; color: var(--text-secondary); margin-top: 8px; }
  /* TECH TRENDS */
  .trend-card { padding: 20px; }
  .trend-icon { font-size: 28px; margin-bottom: 12px; }
  .trend-name { font-size: 15px; font-weight: 700; margin-bottom: 6px; }
  .trend-desc { font-size: 13px; color: var(--text-secondary); line-height: 1.6; margin-bottom: 12px; }
  .trend-bar-wrap { background: rgba(255,255,255,0.06); border-radius: 4px; height: 6px; overflow: hidden; }
  .trend-bar { height: 100%; border-radius: 4px; transition: width 1s ease; }
  .trend-bar-label { display: flex; justify-content: space-between; margin-top: 4px; font-size: 11px; color: var(--text-muted); }
  /* CANADIAN REGIONS */
  .region-card { padding: 22px; }
  .region-city { font-size: 18px; font-weight: 700; margin-bottom: 4px; }
  .region-province { font-size: 12px; color: var(--text-muted); margin-bottom: 14px; }
  .region-price { font-size: 28px; font-weight: 800; color: var(--accent-light); }
  .region-change { font-size: 13px; font-weight: 600; margin-top: 4px; }
  /* SWOT */
  .swot-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
  .swot-card { border-radius: 12px; padding: 20px; }
  .swot-strengths { background: rgba(0,196,140,0.08); border: 1px solid rgba(0,196,140,0.25); }
  .swot-weaknesses { background: rgba(255,59,59,0.08); border: 1px solid rgba(255,59,59,0.25); }
  .swot-opportunities { background: rgba(91,154,111,0.08); border: 1px solid rgba(91,154,111,0.25); }
  .swot-threats { background: rgba(255,176,32,0.08); border: 1px solid rgba(255,176,32,0.25); }
  .swot-label { font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 12px; }
  .swot-strengths .swot-label { color: var(--green); }
  .swot-weaknesses .swot-label { color: var(--red); }
  .swot-opportunities .swot-label { color: #7FBF8E; }
  .swot-threats .swot-label { color: var(--amber); }
  .swot-item { font-size: 13px; color: var(--text-secondary); padding: 6px 0; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; gap: 8px; }
  .swot-item:last-child { border-bottom: none; }
  /* RECOMMENDATIONS */
  .rec-card {
    display: flex; gap: 16px; align-items: flex-start;
    background: var(--bg-card); border: 1px solid var(--border-subtle);
    border-radius: 12px; padding: 18px; margin-bottom: 12px;
    transition: all 0.2s;
  }
  .rec-card:hover { border-color: var(--border); }
  .rec-num {
    width: 36px; height: 36px; border-radius: 50%;
    background: var(--accent-glow); color: var(--accent-light);
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: 14px; flex-shrink: 0;
    border: 1px solid rgba(91,154,111,0.3);
  }
  .rec-body { flex: 1; }
  .rec-title { font-size: 15px; font-weight: 700; margin-bottom: 4px; }
  .rec-desc { font-size: 13px; color: var(--text-secondary); line-height: 1.6; }
  /* CONTENT STRATEGY */
  .cs-category {
    background: var(--bg-card);
    border: 1px solid var(--border-subtle);
    border-radius: 14px; padding: 24px; margin-bottom: 20px;
  }
  .cs-category-title {
    font-size: 16px; font-weight: 700; margin-bottom: 4px;
    display: flex; align-items: center; gap: 10px;
  }
  .cs-category-sub { font-size: 13px; color: var(--text-muted); margin-bottom: 18px; }
  .cs-item {
    padding: 14px; background: var(--bg-secondary);
    border-radius: 10px; margin-bottom: 10px;
    border: 1px solid var(--border-subtle);
  }
  .cs-item:last-child { margin-bottom: 0; }
  .cs-item-title { font-size: 14px; font-weight: 600; color: var(--text-primary); margin-bottom: 4px; }
  .cs-item-angle { font-size: 13px; color: var(--text-secondary); }
  .cs-item-hook { font-size: 12px; color: var(--text-muted); margin-top: 4px; font-style: italic; }
  /* EDGE TABLE */
  .edge-table { width: 100%; border-collapse: collapse; }
  .edge-table th { background: var(--bg-secondary); padding: 10px 14px; text-align: left; font-size: 12px; font-weight: 700; color: var(--text-secondary); border: 1px solid var(--border-subtle); letter-spacing: 0.04em; }
  .edge-table td { padding: 12px 14px; border: 1px solid var(--border-subtle); font-size: 13px; color: var(--text-secondary); vertical-align: top; }
  .edge-table tr:hover td { background: var(--bg-card); }
  .edge-comp { font-weight: 600; color: var(--text-primary); }
  /* FOOTER */
  .footer {
    border-top: 1px solid var(--border-subtle);
    padding: 20px 32px;
    display: flex; justify-content: space-between; align-items: center;
    color: var(--text-muted); font-size: 12px;
    background: var(--bg-secondary);
    margin-top: 48px;
  }
  /* OVERVIEW THREAT TABLE */
  .threat-table { width: 100%; border-collapse: collapse; }
  .threat-table th { background: var(--bg-secondary); padding: 10px 14px; text-align: left; font-size: 12px; font-weight: 700; letter-spacing: 0.04em; color: var(--text-muted); border-bottom: 1px solid var(--border-subtle); }
  .threat-table td { padding: 12px 14px; border-bottom: 1px solid var(--border-subtle); font-size: 13px; }
  .threat-table tr:hover td { background: var(--bg-card); }
  /* INVESTMENT TABLE */
  .inv-table { width: 100%; border-collapse: collapse; margin-top: 12px; }
  .inv-table th { background: var(--bg-secondary); padding: 10px 14px; font-size: 12px; font-weight: 700; color: var(--text-muted); letter-spacing: 0.04em; border-bottom: 1px solid var(--border-subtle); text-align: left; }
  .inv-table td { padding: 10px 14px; border-bottom: 1px solid var(--border-subtle); font-size: 13px; color: var(--text-secondary); }
  .inv-table tr:hover td { background: var(--bg-card); }
  /* RESPONSIVE */
  @media (max-width: 768px) {
    .header { padding: 16px; flex-direction: column; gap: 12px; text-align: center; }
    .content { padding: 16px; }
    .grid-2, .grid-3, .grid-4 { grid-template-columns: 1fr; }
    .swot-grid { grid-template-columns: 1fr; }
    .tab-btn { padding: 12px 12px; font-size: 12px; }
  }
  /* PILL TABS */
  .pill-tabs { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 20px; }
  .pill-tab { padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; cursor: pointer; border: 1px solid var(--border-subtle); background: var(--bg-card); color: var(--text-muted); transition: all 0.2s; }
  .pill-tab:hover { border-color: var(--border); color: var(--text-primary); }
  .pill-tab.active { background: var(--blue-bg); border-color: rgba(91,154,111,0.3); color: var(--accent-light); }
</style>
<style>
  #gate-overlay {
    position: fixed; inset: 0; z-index: 9999;
    background: #111318;
    display: flex; align-items: center; justify-content: center;
  }
  .gate-box {
    width: 100%; max-width: 380px; padding: 48px 40px;
    background: #1C1F2B;
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 16px; text-align: center;
  }
  .gate-logo {
    width: 52px; height: 52px; background: #3D2D7C;
    border-radius: 12px;
    display: inline-flex; align-items: center; justify-content: center;
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 24px; color: #fff; letter-spacing: -1px;
    margin-bottom: 20px;
    box-shadow: 0 4px 20px rgba(61,45,124,0.4);
  }
  .gate-brand {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 26px; font-weight: 500; color: #9B8EC4;
    letter-spacing: 0.5px; margin-bottom: 4px;
  }
  .gate-sub {
    font-size: 11px; color: #5A5B6A;
    text-transform: uppercase; letter-spacing: 2px; margin-bottom: 36px;
  }
  .gate-label {
    display: block; text-align: left;
    font-size: 11px; font-weight: 600; color: #5A5B6A;
    text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;
  }
  #gate-pwd {
    width: 100%; padding: 12px 16px;
    background: #111318;
    border: 1px solid rgba(255,255,255,0.1); border-radius: 8px;
    color: #E8E8EC; font-size: 15px; font-family: inherit;
    outline: none; transition: border-color 0.2s; margin-bottom: 16px;
  }
  #gate-pwd:focus { border-color: rgba(91,154,111,0.5); }
  #gate-btn {
    width: 100%; padding: 13px;
    background: #3D2D7C; color: #fff; border: none;
    border-radius: 8px; font-size: 14px; font-weight: 600;
    font-family: inherit; cursor: pointer; transition: background 0.2s;
  }
  #gate-btn:hover { background: #4e3a9e; }
  #gate-error { margin-top: 14px; font-size: 13px; color: #E05555; min-height: 20px; }
</style>
</head>
<body>

<!-- PASSWORD GATE -->
<div id="gate-overlay">
  <div class="gate-box">
    <div class="gate-logo">sf</div>
    <div class="gate-brand">salefish</div>
    <div class="gate-sub">Competitor Intelligence</div>
    <label class="gate-label" for="gate-pwd">Access Password</label>
    <input type="password" id="gate-pwd" placeholder="Enter password" autocomplete="off">
    <button id="gate-btn" onclick="checkGate()">Access Dashboard</button>
    <div id="gate-error"></div>
  </div>
</div>

<!-- HEADER -->
<div class="header">
  <div class="header-left">
    <div class="logo-mark">sf</div>
    <div>
      <div class="header-logo-text">salefish</div>
      <div class="header-title">Competitor Intelligence Dashboard</div>
      <div class="header-sub">New Home Sales Technology · Toronto</div>
    </div>
  </div>
  <div class="header-meta">
    <div class="header-date" id="header-date"></div>
    <div class="header-badge">14 Competitors · 11 Sections · Live Data</div>
  </div>
</div>

<!-- TAB NAV -->
<div class="tab-nav">
  <button class="tab-btn active" onclick="showTab('overview')">📊 Overview</button>
  <button class="tab-btn" onclick="showTab('profiles')">🏢 Competitor Profiles</button>
  <button class="tab-btn" onclick="showTab('global')">🌍 Global Competitors</button>
  <button class="tab-btn" onclick="showTab('matrix')">⚔️ Feature Matrix</button>
  <button class="tab-btn" onclick="showTab('market')">🌐 PropTech Market</button>
  <button class="tab-btn" onclick="showTab('features')">💡 Feature Suggestions</button>
  <button class="tab-btn" onclick="showTab('trends')">🔮 Tech Trends</button>
  <button class="tab-btn" onclick="showTab('canada')">🍁 Canadian Outlook</button>
  <button class="tab-btn" onclick="showTab('globalopps')">🌎 Global Opportunities</button>
  <button class="tab-btn" onclick="showTab('swot')">🎯 SWOT & Strategy</button>
  <button class="tab-btn" onclick="showTab('content')">✍️ Content Strategy</button>
</div>

<div class="content">

<!-- ========== TAB 1: OVERVIEW ========== -->
<div id="tab-overview" class="tab-panel active">
  <div class="section-header">
    <div class="section-title">Executive Overview</div>
    <div class="section-sub">Competitive landscape snapshot as of <span class="current-date"></span></div>
  </div>

  <!-- TOP METRICS -->
  <div class="grid-4" style="margin-bottom:24px;">
    <div class="card market-stat-card">
      <div class="big-num">14</div>
      <div class="label">Active Competitors Tracked (6 CA · 8 Global)</div>
    </div>
    <div class="card market-stat-card">
      <div class="big-num" style="color:var(--red)">2</div>
      <div class="label">High-Threat Competitors</div>
    </div>
    <div class="card market-stat-card">
      <div class="big-num" style="font-size:32px;">$53–55B</div>
      <div class="label">Global PropTech Market 2026 (projected)</div>
    </div>
    <div class="card market-stat-card">
      <div class="big-num" style="color:var(--green)">+2.9%</div>
      <div class="label">Canadian Housing Transactions YoY (CREA revised forecast)</div>
    </div>
  </div>

  <div class="grid-2">
    <!-- Competitor Threat Summary -->
    <div class="card" style="padding:0;overflow:hidden;">
      <div style="padding:16px 20px;border-bottom:1px solid var(--border-subtle);">
        <div class="card-title">Competitive Threat Summary</div>
      </div>
      <table class="threat-table">
        <thead><tr>
          <th>Competitor</th>
          <th>HQ</th>
          <th>Primary Threat</th>
          <th>Threat Level</th>
        </tr></thead>
        <tbody>
          <tr><td><strong>Blackline App</strong></td><td>Toronto</td><td>US expansion, new VP Sales hire</td><td><span class="badge badge-red">HIGH</span></td></tr>
          <tr><td><strong>Constellation RE Group</strong></td><td>Toronto</td><td>RE/MAX data acquisition, 23 brands</td><td><span class="badge badge-red">HIGH</span></td></tr>
          <tr><td><strong>Lone Wolf Technologies</strong></td><td>Cambridge ON</td><td>API Portal launch, ecosystem play</td><td><span class="badge badge-amber">MODERATE-HIGH</span></td></tr>
          <tr><td><strong>Spark Real Estate</strong></td><td>Vancouver</td><td>Juniper acquisition, full lifecycle</td><td><span class="badge badge-amber">MEDIUM-HIGH</span></td></tr>
          <tr><td><strong>Avesdo</strong></td><td>Vancouver</td><td>VoPay fintech partnership, Kinsman acquisition</td><td><span class="badge badge-amber">MEDIUM-HIGH</span></td></tr>
          <tr><td><strong>Aareas Interactive</strong></td><td>Toronto</td><td>Sales center / kiosk hardware</td><td><span class="badge badge-blue">MEDIUM</span></td></tr>
        </tbody>
      </table>
    </div>

    <!-- Priority Alerts -->
    <div class="card">
      <div class="card-title">🚨 Priority Alerts</div>
      <div class="alert alert-red">
        <div class="alert-icon">🔴</div>
        <div class="alert-text">
          <strong>Constellation Acquires RE/MAX Data Assets (Feb 2026)</strong>
          Constellation Data Labs acquired Seventy3 + Gadberry Group from RE/MAX, gaining listing aggregation and geolocation intelligence. Now powering national data services across RE/MAX tech stack — significantly strengthening their data advantage.
        </div>
      </div>
      <div class="alert alert-red">
        <div class="alert-icon">🔴</div>
        <div class="alert-text">
          <strong>Blackline App — US Market Expansion Push</strong>
          Hired Alexa Johnson as VP of Sales for US & Overseas markets (ex-Sotheby's, Corcoran Group, Keller Williams NYC). Signals aggressive international growth — direct threat expanding beyond Canadian market.
        </div>
      </div>
      <div class="alert alert-amber">
        <div class="alert-icon">🟡</div>
        <div class="alert-text">
          <strong>Avesdo + VoPay Embedded Fintech Partnership</strong>
          Avesdo partnered with VoPay to embed payment processing directly into their TMS. Modernizing pre-sale property deposit operations — directly competing with PropPay LX's value proposition.
        </div>
      </div>
      <div class="alert alert-amber">
        <div class="alert-icon">🟡</div>
        <div class="alert-text">
          <strong>Lone Wolf API Portal Launch (Feb 2026)</strong>
          New centralized API Portal enables third-party integration into Lone Wolf Foundation cloud. Creates ecosystem lock-in — brokers, agents, and tech partners can now build on their platform.
        </div>
      </div>
      <div class="alert alert-amber">
        <div class="alert-icon">🟡</div>
        <div class="alert-text">
          <strong>Lone Wolf Appoints New CEO — Matt Fischer (Feb 17, 2026)</strong>
          Former Bullhorn CEO Matt Fischer named Lone Wolf CEO. His mandate: advance a unified platform, embed AI across the full product suite, and expand integration and ecosystem capabilities. Signals Lone Wolf is accelerating its growth ambitions beyond traditional brokerage software.
        </div>
      </div>
      <div class="alert alert-amber">
        <div class="alert-icon">🟡</div>
        <div class="alert-text">
          <strong>Lone Wolf Boost Integrates into ICE Paragon Connect MLS (Mar 30, 2026)</strong>
          Automated digital advertising tools now embedded directly inside ICE Paragon Connect MLS — agents can launch ad campaigns without leaving their platform. Deepens Lone Wolf's ecosystem lock-in and MLS reach across North America.
        </div>
      </div>
      <div class="alert alert-blue">
        <div class="alert-icon">🔵</div>
        <div class="alert-text">
          <strong>Spark Enters Post-Sale Lifecycle with Juniper Acquisition</strong>
          Spark acquired Juniper (homeowner care & warranty platform), extending beyond sales/marketing into completion and post-sale care. Now a full-lifecycle competitor to SaleFish.
        </div>
      </div>
      <div class="alert alert-amber">
        <div class="alert-icon">🟡</div>
        <div class="alert-text">
          <strong>Zoopla Acquires newhomesforsale.co.uk — UK New Homes Push (Feb 24, 2026)</strong>
          Zoopla acquired the UK's largest specialist new-homes-only portal (200+ developer clients, 2,500 active developments, ~170,000 qualified leads/year). Combined with Yourkeys processing 1,000+ sales/month, Zoopla is now the dominant end-to-end UK new homes platform — upgraded threat for SaleFish if UK expansion is in scope.
        </div>
      </div>
      <div class="alert alert-blue">
        <div class="alert-icon">🔵</div>
        <div class="alert-text">
          <strong>MHub (Malaysia) Emerging as FastKey Successor in SE Asia</strong>
          MHub has processed RM 50B in property value and offers e-SPA, booking, documentation, and HIMS integration. It is actively positioning as the primary digital launch platform for Malaysian developers post-FastKey. Singapore remains without a dominant replacement — the market vacuum for SaleFish-type product is still open in SG.
        </div>
      </div>
      <div class="alert alert-amber">
        <div class="alert-icon">🟡</div>
        <div class="alert-text">
          <strong>Middle East Conflict Adds Geopolitical Risk to Dubai Market (Feb–Apr 2026)</strong>
          Feb 28, 2026: US-Israel launched strikes on Iran; Iran closed Strait of Hormuz; Dubai directly touched by regional conflict for first time in its modern history. A ceasefire was subsequently brokered by Pakistan. Despite record Q1 ($48.1B), off-plan sales fell 21% MoM in March (9,368 transactions). Sentiment shift most acute in off-plan mid-market. Risk of 10–15% correction if conflict re-escalates. SaleFish Dubai opportunity remains but entry timing should be monitored. (Sources: AGBI, Gulf News, The Middle East Insider, Apr 2026)
        </div>
      </div>
      <div class="alert alert-blue">
        <div class="alert-icon">🔵</div>
        <div class="alert-text">
          <strong>BoC April 1 Summary of Deliberations — Hold Stance Confirmed</strong>
          Bank of Canada released its April 1 Summary of Deliberations confirming the Mar 18 hold rationale: wait-and-see on US tariff impact, elevated uncertainty on trade policy. April 29 rate decision (+ full MPR with updated GDP/inflation forecasts) is next major event. Market still pricing 96.5% probability of hold.
        </div>
      </div>
      <div class="alert alert-blue">
        <div class="alert-icon">🔵</div>
        <div class="alert-text">
          <strong>Canada Spring Market: Mixed Start — April 2026 (RBC Economics)</strong>
          First spring weeks show bifurcated market: resale transactions picking up in Toronto, Hamilton, Saskatoon, and Regina — but falling in Vancouver, Fraser Valley, Calgary, and Edmonton. Pent-up demand starting to activate in Ontario but BC and Alberta remain in wait-and-see mode. Apr 16 CREA March data release is next key signal.
        </div>
      </div>
      <div class="alert alert-blue">
        <div class="alert-icon">🔵</div>
        <div class="alert-text">
          <strong>Two Key Market Data Events — April 2026</strong>
          Apr 16: CREA releases March 2026 housing stats (first full spring market read). Apr 29: Bank of Canada rate decision + Monetary Policy Report — first MPR of 2026 with updated growth/inflation forecasts. Both events will set the tone for builder sentiment and presale launch activity in Q2.
        </div>
      </div>
    </div>
  </div>

  <!-- Global Competitor Snapshot -->
  <div style="margin-top:20px;">
    <div class="card" style="padding:0;overflow:hidden;">
      <div style="padding:16px 20px;border-bottom:1px solid var(--border-subtle);">
        <div class="card-title">🌍 Global Competitor Snapshot</div>
      </div>
      <table class="threat-table">
        <thead><tr>
          <th>Competitor</th>
          <th>Region</th>
          <th>Focus</th>
          <th>Threat Level</th>
        </tr></thead>
        <tbody>
          <tr><td><strong>Yourkeys (Zoopla)</strong></td><td>London, UK</td><td>New build reservation & sales progression — Zoopla acquired newhomesforsale.co.uk Feb 2026</td><td><span class="badge badge-amber">MEDIUM-HIGH</span></td></tr>
          <tr><td><strong>Housebuilder Pro</strong></td><td>Shrewsbury, UK</td><td>Builder project mgmt & sales progression</td><td><span class="badge badge-blue">LOW-MEDIUM</span></td></tr>
          <tr><td><strong>ContactBuilder</strong></td><td>UK</td><td>Housebuilder ERP/CRM (15+ year incumbent)</td><td><span class="badge badge-amber">MEDIUM</span></td></tr>
          <tr><td><strong>Runway Proptech</strong></td><td>Australia</td><td>Home builder & community developer software</td><td><span class="badge badge-amber">MEDIUM-HIGH</span></td></tr>
          <tr><td><strong>PropertyGuru FastKey†</strong></td><td>Singapore</td><td>Developer sales platform — SHUT DOWN Oct 2024</td><td><span class="badge badge-green">DEFUNCT — OPPORTUNITY</span></td></tr>
          <tr><td><strong>RealCube</strong></td><td>Dubai, UAE</td><td>Property lifecycle & developer sales mgmt</td><td><span class="badge badge-amber">MEDIUM</span></td></tr>
          <tr><td><strong>Reelly AI</strong></td><td>Dubai, UAE</td><td>Agent-developer off-plan platform (30K+ agents)</td><td><span class="badge badge-amber">MEDIUM</span></td></tr>
          <tr><td><strong>Nawy</strong></td><td>Cairo → UAE/GCC</td><td>Full-stack RE ecosystem ($75M Series A)</td><td><span class="badge badge-blue">LOW-MEDIUM</span></td></tr>
        </tbody>
      </table>
    </div>
  </div>

  <div class="section-divider"></div>

  <!-- Market Snapshot -->
  <div class="section-header">
    <div class="section-title">Market Snapshot</div>
  </div>
  <div class="grid-4">
    <div class="card">
      <div class="card-title">PropTech CAGR</div>
      <div class="metric-value" style="color:var(--accent-light);">16.1%</div>
      <div class="metric-label">Projected annual growth 2026–2035 (Precedence Research)</div>
    </div>
    <div class="card">
      <div class="card-title">CA Avg Home Price</div>
      <div class="metric-value">$664K</div>
      <div class="metric-label">National actual, Feb 2026 (CREA) — ↓0.2% YoY; HPI ↓4.8% YoY; forecast $698,881 (+2.8%) for full 2026</div>
    </div>
    <div class="card">
      <div class="card-title">BoC Policy Rate</div>
      <div class="metric-value" style="color:var(--amber);">2.25%</div>
      <div class="metric-label">Bank of Canada, held Mar 18 2026 — next decision Apr 29 (96.5% probability of hold per Polymarket)</div>
    </div>
    <div class="card">
      <div class="card-title">2026 CA Transactions</div>
      <div class="metric-value">~496K</div>
      <div class="metric-label">CREA revised forecast +2.9% vs 2025 (largest between-forecast downgrade since 2008–09). Next CREA update Apr 16 2026; BoC MPR Apr 29. Feb sales ↓7.8% YoY; GDP forecast 1.1% (BoC Jan MPR, update Apr 29)</div>
    </div>
  </div>
</div>

<!-- ========== TAB 2: COMPETITOR PROFILES ========== -->
<div id="tab-profiles" class="tab-panel">
  <div class="section-header">
    <div class="section-title">Competitor Profiles</div>
    <div class="section-sub">Detailed intelligence on 6 key competitors in the new home sales technology space</div>
  </div>

  <div class="grid-2">

    <!-- BLACKLINE APP -->
    <div class="comp-card">
      <div class="comp-header">
        <div class="comp-logo" style="background:linear-gradient(135deg,#1a1a2e,#16213e);">BL</div>
        <div class="comp-info">
          <div class="comp-name">Blackline App</div>
          <div class="comp-location">📍 Toronto, Ontario</div>
          <span class="badge badge-red">HIGH THREAT</span>
        </div>
      </div>
      <div class="comp-desc">Real estate sales management platform focused on new home worksheet and reservation management. Toronto-based, direct competitor in the GTA presale market. Primary overlap with SaleFish in developer/builder sales management tools.</div>
      <div class="comp-section-title">Recent Intelligence (Mar 2026)</div>
      <div class="news-item">Hired Alexa Johnson as VP of Sales for US & Overseas markets — ex-Sotheby's International Realty, Corcoran Group, Keller Williams NYC</div>
      <div class="news-item">Launched BLACKLINE Cast — cast any content on demand to any screen in the sales center (e.g., suite views from every floor at Reign at Metrotown)</div>
      <div class="news-item">Launched BLACKLINE Engage — new product extension announced via LinkedIn (Apr 2026); full feature details not yet publicly disclosed</div>
      <div class="news-item">Signalling aggressive international expansion beyond Canadian home market</div>
      <div class="news-item">Continues to actively compete for same builder/developer accounts in GTA lowrise and highrise</div>
      <div style="height:16px;"></div>
      <div class="comp-section-title">Key Stats</div>
      <div class="stat-row"><span class="stat-key">Founded</span><span class="stat-val">2018</span></div>
      <div class="stat-row"><span class="stat-key">Market</span><span class="stat-val">Ontario / Canada</span></div>
      <div class="stat-row"><span class="stat-key">Primary Product</span><span class="stat-val">Worksheet & Reservation Mgmt</span></div>
      <div class="stat-row"><span class="stat-key">Differentiator</span><span class="stat-val">Local Toronto focus, deep GTA relationships</span></div>
    </div>

    <!-- CONSTELLATION REAL ESTATE GROUP -->
    <div class="comp-card">
      <div class="comp-header">
        <div class="comp-logo" style="background:linear-gradient(135deg,#1a2e24,#2a4a36);">CR</div>
        <div class="comp-info">
          <div class="comp-name">Constellation Real Estate Group</div>
          <div class="comp-location">📍 Toronto, Ontario (Constellation Software subsidiary)</div>
          <span class="badge badge-red">HIGH THREAT</span>
        </div>
      </div>
      <div class="comp-desc">Subsidiary of Constellation Software Inc., a TSX-listed serial acquirer. Operates a portfolio of real estate software companies targeting property management, brokerage, and new home sales segments. M&A-driven growth model that consolidates niche real estate software vendors.</div>
      <div class="comp-section-title">Recent Intelligence (Mar 2026)</div>
      <div class="news-item">Feb 2026: Constellation Data Labs acquired Seventy3 + Gadberry Group from RE/MAX — listing aggregation & geolocation intelligence</div>
      <div class="news-item">Concurrent deal: Constellation now powering national data services across entire RE/MAX technology stack</div>
      <div class="news-item">Portfolio grew to 23 brands — Showcase IDX joined (12M+ consumer users on agent/broker websites)</div>
      <div class="news-item">Dec 2024: Unified all RE software under one brand; appointed Brant Morwald as President. 11 products on 2025 Tech 200 list — most of any company</div>
      <div style="height:16px;"></div>
      <div class="comp-section-title">Key Stats</div>
      <div class="stat-row"><span class="stat-key">Parent</span><span class="stat-val">Constellation Software Inc. (TSX: CSU)</span></div>
      <div class="stat-row"><span class="stat-key">Model</span><span class="stat-val">M&A / Buy-and-hold vertical SaaS</span></div>
      <div class="stat-row"><span class="stat-key">Strategy</span><span class="stat-val">Consolidation & market rollup</span></div>
      <div class="stat-row"><span class="stat-key">Capital Depth</span><span class="stat-val">Significant (CSU market cap ~$80B+)</span></div>
    </div>

    <!-- LONE WOLF TECHNOLOGIES -->
    <div class="comp-card">
      <div class="comp-header">
        <div class="comp-logo" style="background:linear-gradient(135deg,#1a3a4a,#0d5c7a);">LW</div>
        <div class="comp-info">
          <div class="comp-name">Lone Wolf Technologies</div>
          <div class="comp-location">📍 Cambridge, Ontario</div>
          <span class="badge badge-amber">MODERATE-HIGH</span>
        </div>
      </div>
      <div class="comp-desc">North America's largest residential real estate software company, serving 1.5M+ agents and brokerages across Canada and the US. Has been expanding beyond traditional brokerage tools into transaction management and increasingly the new home presale segment. Significant technology and customer base advantages.</div>
      <div class="comp-section-title">Recent Intelligence (Mar 2026)</div>
      <div class="news-item">Feb 17 2026: Matt Fischer (ex-Bullhorn CEO) appointed as new CEO — mandate to advance unified platform, embed AI across product suite, and accelerate ecosystem growth</div>
      <div class="news-item">Feb 2026: Launched Lone Wolf API Portal — centralized hub for brokers, agents & tech partners to integrate with Lone Wolf Foundation cloud</div>
      <div class="news-item">Feb 2026: Announced 2026 PropTech Advisory Board with industry leaders guiding innovation roadmap</div>
      <div class="news-item">Mar 2026: Two executives named RISMedia 2026 Newsmakers — Kyle Hunter (Trailblazer) and Jake Hamilton (Futurist)</div>
      <div class="news-item">Mar 30 2026: Lone Wolf Boost integrated into ICE Paragon Connect MLS — agents get automated digital advertising tools without leaving their MLS platform</div>
      <div style="height:16px;"></div>
      <div class="comp-section-title">Key Stats</div>
      <div class="stat-row"><span class="stat-key">Founded</span><span class="stat-val">1989</span></div>
      <div class="stat-row"><span class="stat-key">Users</span><span class="stat-val">1.5M+ agents & brokerages</span></div>
      <div class="stat-row"><span class="stat-key">Market</span><span class="stat-val">Canada & USA</span></div>
      <div class="stat-row"><span class="stat-key">Differentiator</span><span class="stat-val">Scale, brokerage ecosystem, CREA relationships</span></div>
    </div>

    <!-- AVESDO -->
    <div class="comp-card">
      <div class="comp-header">
        <div class="comp-logo" style="background:linear-gradient(135deg,#0d2e4a,#0a4a6e);">AV</div>
        <div class="comp-info">
          <div class="comp-name">Avesdo</div>
          <div class="comp-location">📍 Vancouver, British Columbia</div>
          <span class="badge badge-blue">MEDIUM</span>
        </div>
      </div>
      <div class="comp-desc">Vancouver-based new home sales platform specializing in deposit management, buyer CRM, and contract lifecycle for presale developments. Strong presence in BC market with growing national ambitions. Most direct feature-for-feature overlap with SaleFish's core product.</div>
      <div class="comp-section-title">Recent Intelligence (Mar 2026)</div>
      <div class="news-item">Partnered with VoPay (embedded fintech) to modernize pre-sale property payment operations — embedded payment processing directly in TMS</div>
      <div class="news-item">Acquired Kinsman — trusted digital strategy advisor to top-tier Canadian developers for new home sales & marketing</div>
      <div class="news-item">Q2 product update: Responsive OffPlan for realtors, API V2 with usage indicators and add/edit plan for enterprise</div>
      <div class="news-item">Nick Moshenko joined as Chief Product Officer (CPO) — signals increased investment in product leadership and roadmap velocity</div>
      <div class="news-item">Continuing national expansion beyond BC stronghold with enhanced platform capabilities</div>
      <div style="height:16px;"></div>
      <div class="comp-section-title">Key Stats</div>
      <div class="stat-row"><span class="stat-key">Founded</span><span class="stat-val">2016</span></div>
      <div class="stat-row"><span class="stat-key">Primary Market</span><span class="stat-val">British Columbia (expanding nationally)</span></div>
      <div class="stat-row"><span class="stat-key">Strength</span><span class="stat-val">Deposit management, buyer CRM</span></div>
      <div class="stat-row"><span class="stat-key">Funding</span><span class="stat-val">Private / VC-backed</span></div>
    </div>

    <!-- SPARK REAL ESTATE -->
    <div class="comp-card">
      <div class="comp-header">
        <div class="comp-logo" style="background:linear-gradient(135deg,#2a1a0e,#5c3010);">SR</div>
        <div class="comp-info">
          <div class="comp-name">Spark Real Estate</div>
          <div class="comp-location">📍 Global (North America focused)</div>
          <span class="badge badge-amber">MEDIUM-HIGH</span>
        </div>
      </div>
      <div class="comp-desc">Sales and marketing platform for new residential developments. Focuses on digital sales centers, online reservation, and marketing automation for builders and developers. Positioned at the marketing/sales funnel layer with integrations into CRM platforms. Growing footprint in Canadian new home market.</div>
      <div class="comp-section-title">Recent Intelligence (Mar 2026)</div>
      <div class="news-item">Oct 2024: Acquired Juniper — modern homeowner care & warranty platform for residential developers. Extends Spark beyond sales into completion & post-sale care</div>
      <div class="news-item">Active in 80+ cities across North America. "Smart Groups" feature for granular automated database segmentation & targeted marketing</div>
      <div class="news-item">Strategic pivot: expanding full development lifecycle coverage — from marketing to sales to warranty/homeowner care</div>
      <div class="news-item">Becoming a direct lifecycle competitor to SaleFish as they close the gap between marketing and post-sale</div>
      <div style="height:16px;"></div>
      <div class="comp-section-title">Key Stats</div>
      <div class="stat-row"><span class="stat-key">Market Focus</span><span class="stat-val">New home developer sales & marketing</span></div>
      <div class="stat-row"><span class="stat-key">Strength</span><span class="stat-val">Marketing layer, lead funnel, digital sales centers</span></div>
      <div class="stat-row"><span class="stat-key">Integration</span><span class="stat-val">CRM platforms (Salesforce and others)</span></div>
      <div class="stat-row"><span class="stat-key">Differentiator</span><span class="stat-val">Modern UX, marketing automation focus</span></div>
    </div>

    <!-- AAREAS INTERACTIVE -->
    <div class="comp-card">
      <div class="comp-header">
        <div class="comp-logo" style="background:linear-gradient(135deg,#0a2a1e,#0a4a32);">AA</div>
        <div class="comp-info">
          <div class="comp-name">Aareas Interactive</div>
          <div class="comp-location">📍 Toronto, Ontario</div>
          <span class="badge badge-blue">MEDIUM</span>
        </div>
      </div>
      <div class="comp-desc">Toronto-based interactive sales center technology company specializing in touchscreen presentations, virtual tours, and immersive buyer experiences for new home developments. Hardware + software model focused on in-person sales centers rather than end-to-end sales management.</div>
      <div class="comp-section-title">Recent Intelligence (Mar 2026)</div>
      <div class="news-item">HUD Secretary Scott Turner personally visited Aareas' booth at MHI Congress & Expo 2025 — highlighted Virtual Design Center as exactly the kind of innovation the housing industry needs</div>
      <div class="news-item">Won Gold & Silver Muse Awards 2024 — recognizing excellence in design, innovation, and creativity (17,200+ global entrants)</div>
      <div class="news-item">Exhibiting at International Builders' Show (IBS) 2026 — continued engagement with manufactured housing & new home builder segment</div>
      <div class="news-item">247 Sales Center platform, Virtual Design Center (3D photorealistic visualizer), interactive kiosks — returned to manufactured housing with enhanced virtual tools</div>
      <div class="news-item">Deep relationships with major GTA builders for sales center design and technology</div>
      <div class="news-item">Apr 28–30 2026: Exhibiting at industry trade show in St. Louis — continued expansion into manufactured/new home builder market</div>
      <div style="height:16px;"></div>
      <div class="comp-section-title">Key Stats</div>
      <div class="stat-row"><span class="stat-key">Founded</span><span class="stat-val">2005 (est.)</span></div>
      <div class="stat-row"><span class="stat-key">Model</span><span class="stat-val">Hardware + Software (sales center tech)</span></div>
      <div class="stat-row"><span class="stat-key">Strength</span><span class="stat-val">Immersive buyer experience, touchscreens</span></div>
      <div class="stat-row"><span class="stat-key">Differentiator</span><span class="stat-val">Physical + digital sales center integration</span></div>
    </div>

  </div>
</div>

<!-- ========== TAB: GLOBAL COMPETITORS ========== -->
<div id="tab-global" class="tab-panel">
  <div class="section-header">
    <div class="section-title">Global Competitor Profiles</div>
    <div class="section-sub">International players in new home / off-plan sales technology across 4 key regions</div>
  </div>

  <!-- UNITED KINGDOM -->
  <div style="margin-bottom:8px;"><span style="font-size:14px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.08em;">🇬🇧 United Kingdom</span></div>
  <div class="grid-2" style="margin-bottom:24px;">

    <!-- YOURKEYS / ZOOPLA -->
    <div class="comp-card">
      <div class="comp-header">
        <div class="comp-logo" style="background:linear-gradient(135deg,#1a2a4a,#2a4a7a);">YK</div>
        <div class="comp-info">
          <div class="comp-name">Yourkeys (Zoopla)</div>
          <div class="comp-location">📍 London, United Kingdom</div>
          <span class="badge badge-amber">MEDIUM</span>
        </div>
      </div>
      <div class="comp-desc">End-to-end new homes transaction platform — reservation engine, sales progression, AML/identity verification, and housebuilder CRM. Acquired by Zoopla (Silver Lake-backed) in 2021. Creates a shared data layer connecting housebuilders, conveyancers, mortgage brokers, and lenders to accelerate new build sales.</div>
      <div class="comp-section-title">Recent Intelligence (Apr 2026)</div>
      <div class="news-item">Feb 24, 2026: Zoopla acquired newhomesforsale.co.uk — UK's largest specialist new-homes-only portal (200+ developer clients, 2,500 active developments, ~170K qualified leads/year)</div>
      <div class="news-item">Yourkeys now processing 1,000+ new home sales/month across 50+ UK housebuilder clients</div>
      <div class="news-item">Launched industry-first shared ownership compliance gateway — digital onboarding for shared ownership transactions</div>
      <div class="news-item">Zoopla reports 21% YoY rise in new homes searches and 53% uplift in leads to builders in 2025</div>
      <div class="news-item">2026 strategy: targeting the 70% of buyers open to new builds but not actively searching using AI-driven audience matching</div>
      <div style="height:16px;"></div>
      <div class="comp-section-title">Key Stats</div>
      <div class="stat-row"><span class="stat-key">Parent</span><span class="stat-val">Zoopla (Silver Lake) — now owns newhomesforsale.co.uk too</span></div>
      <div class="stat-row"><span class="stat-key">Market</span><span class="stat-val">UK New Builds (dominant portal + transaction platform)</span></div>
      <div class="stat-row"><span class="stat-key">Scale</span><span class="stat-val">1,000+ sales/month; 50+ builders; 170K+ qualified leads/year</span></div>
      <div class="stat-row"><span class="stat-key">Differentiator</span><span class="stat-val">End-to-end: portal listings → reservation → sales progression → lender integration</span></div>
    </div>

    <!-- HOUSEBUILDER PRO -->
    <div class="comp-card">
      <div class="comp-header">
        <div class="comp-logo" style="background:linear-gradient(135deg,#2a1a3e,#4a2a6e);">HP</div>
        <div class="comp-info">
          <div class="comp-name">Housebuilder Pro</div>
          <div class="comp-location">📍 Shrewsbury, United Kingdom</div>
          <span class="badge badge-blue">LOW-MEDIUM</span>
        </div>
      </div>
      <div class="comp-desc">End-to-end project management platform for new home builders by Shoothill. Covers sales progression from reservation to completion, CRM, snagging/aftercare, and customer journey tracking. Reservation forms completed and stored live in-system. Enterprise-priced at GBP 490/user/month.</div>
      <div class="comp-section-title">Recent Intelligence</div>
      <div class="news-item">Active marketing push in 2025–2026 around accelerating sales progression</div>
      <div class="news-item">Enterprise pricing (GBP 490/user/month) positions for mid-to-large UK housebuilders</div>
      <div class="news-item">Built by Shoothill — a broader software consultancy, not a pure-play proptech</div>
      <div style="height:16px;"></div>
      <div class="comp-section-title">Key Stats</div>
      <div class="stat-row"><span class="stat-key">Parent</span><span class="stat-val">Shoothill Ltd</span></div>
      <div class="stat-row"><span class="stat-key">Market</span><span class="stat-val">UK Housebuilders</span></div>
      <div class="stat-row"><span class="stat-key">Primary Product</span><span class="stat-val">Project Management & Sales Progression</span></div>
      <div class="stat-row"><span class="stat-key">Differentiator</span><span class="stat-val">End-to-end build + sales + aftercare</span></div>
    </div>

    <!-- CONTACTBUILDER -->
    <div class="comp-card">
      <div class="comp-header">
        <div class="comp-logo" style="background:linear-gradient(135deg,#1a3a2a,#2a5a3a);">CB</div>
        <div class="comp-info">
          <div class="comp-name">ContactBuilder</div>
          <div class="comp-location">📍 United Kingdom</div>
          <span class="badge badge-amber">MEDIUM</span>
        </div>
      </div>
      <div class="comp-desc">Full ERP and project management platform for housebuilders — plot management, lead management, marketing, sales progression, completion, and aftercare. The longest-established platform in the UK housebuilder niche with 15+ years in market. 50+ clients from top-10 PLCs to niche developers.</div>
      <div class="comp-section-title">Recent Intelligence (Apr 2026)</div>
      <div class="news-item">Launched "ContactBuilder Professional Edition" — comprehensive UI refresh covering build, marketing, sales, and aftercare touchpoints</div>
      <div class="news-item">Dominant incumbent in UK housebuilder CRM/ERP — 50+ clients including top-10 UK housebuilders</div>
      <div class="news-item">18 years in market — deepest feature set and housebuilder relationships in the UK</div>
      <div class="news-item">Still lacks modern fintech/embedded payments integration — SaleFish differentiator opportunity remains</div>
      <div style="height:16px;"></div>
      <div class="comp-section-title">Key Stats</div>
      <div class="stat-row"><span class="stat-key">Founded</span><span class="stat-val">~2008 (15+ years)</span></div>
      <div class="stat-row"><span class="stat-key">Market</span><span class="stat-val">UK Housebuilders & Housing Associations</span></div>
      <div class="stat-row"><span class="stat-key">Primary Product</span><span class="stat-val">Housebuilder ERP / CRM</span></div>
      <div class="stat-row"><span class="stat-key">Differentiator</span><span class="stat-val">Incumbent depth, 50+ builder clients</span></div>
    </div>

  </div>

  <!-- AUSTRALIA & NEW ZEALAND -->
  <div style="margin-bottom:8px;"><span style="font-size:14px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.08em;">🇦🇺 Australia & New Zealand</span></div>
  <div class="grid-2" style="margin-bottom:24px;">

    <!-- RUNWAY PROPTECH -->
    <div class="comp-card">
      <div class="comp-header">
        <div class="comp-logo" style="background:linear-gradient(135deg,#2a3a1a,#4a6a2a);">RP</div>
        <div class="comp-info">
          <div class="comp-name">Runway Proptech</div>
          <div class="comp-location">📍 Australia (teams in India, Europe, USA)</div>
          <span class="badge badge-amber">MEDIUM-HIGH</span>
        </div>
      </div>
      <div class="comp-desc">Leading software provider for Australian and US home builders and community developers. Runway 7 platform covers inventory management, lot fits, marketing automation, sales workflows, and developer portals. Specialized tools for house-and-land packaging and lot fit automation. Founded 1997.</div>
      <div class="comp-section-title">Recent Intelligence</div>
      <div class="news-item">Active development of AI-powered tools and Runway 7 platform</div>
      <div class="news-item">50+ proptech specialists with teams across Australia, India, Europe, and USA</div>
      <div class="news-item">Focused primarily on land/community development rather than high-rise condo presales — a gap SaleFish could fill</div>
      <div style="height:16px;"></div>
      <div class="comp-section-title">Key Stats</div>
      <div class="stat-row"><span class="stat-key">Founded</span><span class="stat-val">1997</span></div>
      <div class="stat-row"><span class="stat-key">Market</span><span class="stat-val">Australia & USA</span></div>
      <div class="stat-row"><span class="stat-key">Primary Product</span><span class="stat-val">Builder Sales & Lot Fit Automation</span></div>
      <div class="stat-row"><span class="stat-key">Differentiator</span><span class="stat-val">House-and-land packaging, community dev portals</span></div>
    </div>

  </div>

  <!-- ASIA-PACIFIC -->
  <div style="margin-bottom:8px;"><span style="font-size:14px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.08em;">🌏 Asia-Pacific</span></div>
  <div class="grid-2" style="margin-bottom:24px;">

    <!-- PROPERTYGURU FASTKEY -->
    <div class="comp-card">
      <div class="comp-header">
        <div class="comp-logo" style="background:linear-gradient(135deg,#3a1a1a,#6a2a2a);">FK</div>
        <div class="comp-info">
          <div class="comp-name">PropertyGuru FastKey †</div>
          <div class="comp-location">📍 Singapore (SHUT DOWN Oct 2024)</div>
          <span class="badge badge-green">DEFUNCT — OPPORTUNITY</span>
        </div>
      </div>
      <div class="comp-desc">Was the dominant SaaS developer sales platform in Southeast Asia — automated the full project sales cycle from launch to close. Served 500+ projects from 100+ developers with inventory management, real-time sales tracking, and a 30,000+ agent marketplace. Part of PropertyGuru Group (NYSE-listed). Shutdown creates a major market vacuum.</div>
      <div class="comp-section-title">Key Events</div>
      <div class="news-item">Jul 2024: Indonesia service discontinued</div>
      <div class="news-item">Oct 2024: Malaysia and Singapore services shut down — full platform closure (app removed Dec 2024)</div>
      <div class="news-item">500+ projects and 100+ developers left without a platform — market vacuum for SaleFish-type product</div>
      <div class="news-item">Malaysia: MHub has emerged as the primary successor — e-SPA, booking, HIMS integration, RM 50B+ transacted. But MHub is Malaysia-only.</div>
      <div class="news-item">Singapore: No dominant replacement identified as of Apr 2026 — vacuum remains open for SaleFish entry</div>
      <div style="height:16px;"></div>
      <div class="comp-section-title">Opportunity Assessment</div>
      <div class="stat-row"><span class="stat-key">Market Gap</span><span class="stat-val badge badge-red">CRITICAL — No Replacement</span></div>
      <div class="stat-row"><span class="stat-key">Region</span><span class="stat-val">Singapore, Malaysia, Indonesia</span></div>
      <div class="stat-row"><span class="stat-key">2026 Pipeline</span><span class="stat-val">22 new launches, ~9,700 units (Singapore alone)</span></div>
      <div class="stat-row"><span class="stat-key">SaleFish Fit</span><span class="stat-val badge badge-green">VERY HIGH</span></div>
    </div>

    <!-- NAWY -->
    <div class="comp-card">
      <div class="comp-header">
        <div class="comp-logo" style="background:linear-gradient(135deg,#1a2e4a,#2a4e6a);">NW</div>
        <div class="comp-info">
          <div class="comp-name">Nawy</div>
          <div class="comp-location">📍 Cairo, Egypt → UAE/GCC expansion</div>
          <span class="badge badge-blue">LOW-MEDIUM</span>
        </div>
      </div>
      <div class="comp-desc">Africa's largest proptech — full-stack real estate ecosystem combining property listings, brokerage, fractional ownership (Nawy Shares), and digital financing. GMV surged from $38M (2020) to $1.4B+ (2024). Aggressively expanding into Gulf markets after acquiring SmartCrowd (Dubai-based fractional ownership).</div>
      <div class="comp-section-title">Recent Intelligence (Apr 2026)</div>
      <div class="news-item">May 2025: $75M Series A — one of the largest Series A rounds for an African startup</div>
      <div class="news-item">Jul 2025: Acquired SmartCrowd (Dubai fractional ownership platform) to enter GCC market</div>
      <div class="news-item">Dec 2025: Launched +Partners platform — broker support ecosystem with integrated tech, financial solutions, and marketing tools for 6,500+ broker-company network</div>
      <div class="news-item">2026 plans: broaden regional footprint across MENA, introduce new PropTech solutions, expand broker network internationally</div>
      <div class="news-item">1M+ monthly visitors; expanding to Saudi Arabia, UAE, and Gulf markets</div>
      <div class="news-item">Different positioning (marketplace/brokerage) but aggressive funding, 6,500+ broker partners, and expansion trajectory to watch</div>
      <div style="height:16px;"></div>
      <div class="comp-section-title">Key Stats</div>
      <div class="stat-row"><span class="stat-key">Funding</span><span class="stat-val">$75M Series A (May 2025)</span></div>
      <div class="stat-row"><span class="stat-key">GMV</span><span class="stat-val">$1.4B+ (2024)</span></div>
      <div class="stat-row"><span class="stat-key">Model</span><span class="stat-val">Full-stack marketplace + fintech</span></div>
      <div class="stat-row"><span class="stat-key">Expansion</span><span class="stat-val">Egypt → UAE, Saudi Arabia, GCC</span></div>
    </div>

  </div>

  <!-- MIDDLE EAST -->
  <div style="margin-bottom:8px;"><span style="font-size:14px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.08em;">🇦🇪 Middle East</span></div>
  <div class="grid-2" style="margin-bottom:24px;">

    <!-- REALCUBE -->
    <div class="comp-card">
      <div class="comp-header">
        <div class="comp-logo" style="background:linear-gradient(135deg,#3a2a1a,#6a4a2a);">RC</div>
        <div class="comp-info">
          <div class="comp-name">RealCube</div>
          <div class="comp-location">📍 Dubai, UAE</div>
          <span class="badge badge-amber">MEDIUM</span>
        </div>
      </div>
      <div class="comp-desc">Integrated software platform covering property management, community management, sales and leasing, finance/operations, and facilities management. Serves major UAE developers with end-to-end property lifecycle management from sales to handover. Real-time data connecting sales teams, brokers, and buyers.</div>
      <div class="comp-section-title">Recent Intelligence (Apr 2026)</div>
      <div class="news-item">Launched NOVA — AI control centre delivering real-time insights, predictive automation, and data-driven decision-making integrated into the RealCube ecosystem</div>
      <div class="news-item">2025: Digitized entire property lifecycle for a major UAE developer — end-to-end from lead capture → booking → handover → community management</div>
      <div class="news-item">UAE/GCC-focused — no international expansion announced</div>
      <div class="news-item">Still lacks specialized presale deposit/payment management — SaleFish differentiator remains intact</div>
      <div style="height:16px;"></div>
      <div class="comp-section-title">Key Stats</div>
      <div class="stat-row"><span class="stat-key">Market</span><span class="stat-val">UAE / GCC Developers</span></div>
      <div class="stat-row"><span class="stat-key">Primary Product</span><span class="stat-val">Property Lifecycle Platform</span></div>
      <div class="stat-row"><span class="stat-key">Strength</span><span class="stat-val">Full lifecycle: sales → handover → community</span></div>
      <div class="stat-row"><span class="stat-key">Differentiator</span><span class="stat-val">Deep UAE developer relationships</span></div>
    </div>

    <!-- REELLY AI -->
    <div class="comp-card">
      <div class="comp-header">
        <div class="comp-logo" style="background:linear-gradient(135deg,#1a1a3e,#2a2a6e);">RL</div>
        <div class="comp-info">
          <div class="comp-name">Reelly AI</div>
          <div class="comp-location">📍 Dubai, UAE</div>
          <span class="badge badge-amber">MEDIUM</span>
        </div>
      </div>
      <div class="comp-desc">B2B platform connecting 60,000+ agents across UAE and international markets. Covers 1,350+ off-plan projects with AI-powered branded presentation generation, sales offer creation, and developer-agent connectivity. Strategic collaboration with Tether (USDT) for crypto-enabled RE transactions.</div>
      <div class="comp-section-title">Recent Intelligence (Apr 2026)</div>
      <div class="news-item">Mar 25, 2026: Partnership with EasyWill — UAE real estate agents can now create legally compliant UAE wills via Reelly, expanding platform beyond property search/sales into wealth services</div>
      <div class="news-item">Named Top 10 Market Maker at PropTech Connect Dubai 2026</div>
      <div class="news-item">60,000+ agents, 1,350+ off-plan projects on platform (updated scale)</div>
      <div class="news-item">Strategic collaboration with Tether (USDT) for crypto-enabled real estate transactions</div>
      <div class="news-item">Focuses on presentation/discovery side — still lacks end-to-end transaction management (SaleFish opportunity)</div>
      <div style="height:16px;"></div>
      <div class="comp-section-title">Key Stats</div>
      <div class="stat-row"><span class="stat-key">Market</span><span class="stat-val">UAE + International</span></div>
      <div class="stat-row"><span class="stat-key">Scale</span><span class="stat-val">60,000+ agents, 1,350+ projects</span></div>
      <div class="stat-row"><span class="stat-key">Primary Product</span><span class="stat-val">AI Presentations & Agent-Developer Platform</span></div>
      <div class="stat-row"><span class="stat-key">Differentiator</span><span class="stat-val">AI content generation, crypto payments, expanding into wealth services</span></div>
    </div>

  </div>

  <div class="section-divider"></div>

  <div class="section-header">
    <div class="section-title">Potential Integration Partners</div>
    <div class="section-sub">Strategic partnership opportunities across 3D visualization, developer marketing, new home sales, and fintech</div>
  </div>

  <!-- 3D VISUALIZATION & VIRTUAL TOURS -->
  <div class="card" style="margin-bottom:20px;">
    <div class="card-title">🥽 3D Visualization & Virtual Tours</div>
    <table class="inv-table">
      <thead><tr><th>Company</th><th>Region</th><th>What They Do</th><th>Partnership Value</th><th>Fit</th></tr></thead>
      <tbody>
        <tr><td><strong>Matterport</strong></td><td>USA (Global)</td><td>Industry-leading 3D digital twin platform — spatial data for buildings, virtual walkthroughs</td><td>Integrate 3D property tours into SaleFish buyer portal</td><td><span class="badge badge-green">STRONG FIT</span></td></tr>
        <tr><td><strong>EnvisionVR</strong></td><td>Australia / Global</td><td>VR property tours and immersive buyer experience platform for new developments</td><td>White-label VR tour integration for presale marketing</td><td><span class="badge badge-green">STRONG FIT</span></td></tr>
        <tr><td><strong>EyeSpy360</strong></td><td>UK</td><td>360° virtual tour platform with interactive floor plans and live guided tours</td><td>Embed interactive tours within SaleFish sales center workflow</td><td><span class="badge badge-amber">GOOD FIT</span></td></tr>
        <tr><td><strong>Insite VR (Autodesk)</strong></td><td>USA (Global)</td><td>VR visualization for architecture and construction — walkthrough unbuilt spaces</td><td>Pre-construction visualization for presale buyer experience</td><td><span class="badge badge-amber">GOOD FIT</span></td></tr>
        <tr><td><strong>Coohom</strong></td><td>China / Global</td><td>AI-powered 3D interior design and visualization with photorealistic rendering</td><td>Buyer design customization tool integrated into unit selection</td><td><span class="badge badge-blue">EXPLORE</span></td></tr>
      </tbody>
    </table>
  </div>

  <!-- DEVELOPER MARKETING AGENCIES -->
  <div class="card" style="margin-bottom:20px;">
    <div class="card-title">📢 Developer Marketing Agencies</div>
    <table class="inv-table">
      <thead><tr><th>Company</th><th>Region</th><th>What They Do</th><th>Partnership Value</th><th>Fit</th></tr></thead>
      <tbody>
        <tr><td><strong>Jesson + Co Creative</strong></td><td>Vancouver, BC</td><td>Specialized new home development marketing — branding, digital, sales center design</td><td>Co-market SaleFish to mutual developer clients; joint sales center packages</td><td><span class="badge badge-green">STRONG FIT</span></td></tr>
        <tr><td><strong>Rennie</strong></td><td>Vancouver, BC</td><td>Full-service real estate marketing, intelligence, and advisory for developers</td><td>Integration partner — Rennie manages presale launches that SaleFish could power</td><td><span class="badge badge-green">STRONG FIT</span></td></tr>
        <tr><td><strong>The Agency (AU)</strong></td><td>Australia</td><td>Boutique real estate group with strong new development marketing division</td><td>Channel partner for Australian market entry</td><td><span class="badge badge-amber">GOOD FIT</span></td></tr>
        <tr><td><strong>Savills New Homes</strong></td><td>UK / Global</td><td>Global real estate services — new homes division handles developer sales & marketing in UK</td><td>Enterprise channel partner for UK new build market</td><td><span class="badge badge-amber">GOOD FIT</span></td></tr>
        <tr><td><strong>Allsopp & Allsopp</strong></td><td>Dubai, UAE</td><td>Leading UAE brokerage with dedicated off-plan/new development division</td><td>Channel partner for Dubai off-plan market entry</td><td><span class="badge badge-blue">EXPLORE</span></td></tr>
      </tbody>
    </table>
  </div>

  <!-- NEW HOME SALES BROKERAGES -->
  <div class="card" style="margin-bottom:20px;">
    <div class="card-title">🏠 New Home Sales Brokerages</div>
    <table class="inv-table">
      <thead><tr><th>Company</th><th>Region</th><th>What They Do</th><th>Partnership Value</th><th>Fit</th></tr></thead>
      <tbody>
        <tr><td><strong>Baker Real Estate</strong></td><td>Toronto, ON</td><td>Canada's largest new home sales and marketing company — 80,000+ units sold</td><td>Flagship Canadian channel partner; SaleFish powering Baker's launches</td><td><span class="badge badge-green">STRONG FIT</span></td></tr>
        <tr><td><strong>MLA Canada</strong></td><td>Vancouver, BC</td><td>Western Canada's leading new home advisory — marketing, sales, and research</td><td>Channel partner for BC market expansion; presale launch integration</td><td><span class="badge badge-green">STRONG FIT</span></td></tr>
        <tr><td><strong>Knight Frank New Homes</strong></td><td>UK / Global</td><td>Global real estate consultancy — new homes division handles developer launches in UK, Asia, Middle East</td><td>Enterprise partner for multi-region international expansion</td><td><span class="badge badge-amber">GOOD FIT</span></td></tr>
        <tr><td><strong>IQI Global</strong></td><td>Malaysia / SE Asia</td><td>30,000+ agents across 25+ countries — strong new launch and developer sales in SE Asia</td><td>Channel partner to fill FastKey vacuum; SaleFish as their platform replacement</td><td><span class="badge badge-green">STRONG FIT</span></td></tr>
        <tr><td><strong>Betterhomes</strong></td><td>Dubai, UAE</td><td>One of UAE's largest brokerages with dedicated off-plan new development team</td><td>Channel partner for Dubai market entry; off-plan sales workflow</td><td><span class="badge badge-blue">EXPLORE</span></td></tr>
      </tbody>
    </table>
  </div>

  <!-- FINTECH / PAYMENTS -->
  <div class="card">
    <div class="card-title">💳 Fintech / Payments</div>
    <table class="inv-table">
      <thead><tr><th>Company</th><th>Region</th><th>What They Do</th><th>Partnership Value</th><th>Fit</th></tr></thead>
      <tbody>
        <tr><td><strong>VoPay</strong></td><td>Vancouver, BC</td><td>Embedded payment infrastructure — EFT, PAD, real-time payments for platforms</td><td>Already partnered with Avesdo — SaleFish's PropPay LX competes directly</td><td><span class="badge badge-red">COMPETITOR PARTNER</span></td></tr>
        <tr><td><strong>Stripe Connect</strong></td><td>USA (Global)</td><td>Platform payments infrastructure — marketplace payouts, escrow, multi-party</td><td>International payment rail for PropPay LX expansion beyond Canadian PAD</td><td><span class="badge badge-green">STRONG FIT</span></td></tr>
        <tr><td><strong>Peach Payments</strong></td><td>South Africa / UAE</td><td>Payment gateway for emerging markets — supports local payment methods across Africa and Middle East</td><td>Payment infrastructure for Dubai/GCC market entry</td><td><span class="badge badge-amber">GOOD FIT</span></td></tr>
        <tr><td><strong>Stake (Fractional)</strong></td><td>Dubai, UAE</td><td>Fractional real estate ownership platform — $31M Series B (2026)</td><td>Fractional ownership integration for presale units — future product extension</td><td><span class="badge badge-blue">EXPLORE</span></td></tr>
        <tr><td><strong>Huspy</strong></td><td>Dubai, UAE</td><td>AI-powered mortgage platform — digital pre-approvals, documentation automation ($101M+ VC)</td><td>Mortgage pre-approval integration into buyer journey</td><td><span class="badge badge-amber">GOOD FIT</span></td></tr>
      </tbody>
    </table>
  </div>

</div>

<!-- ========== TAB 3: FEATURE MATRIX ========== -->
<div id="tab-matrix" class="tab-panel">
  <div class="section-header">
    <div class="section-title">Feature Comparison Matrix</div>
    <div class="section-sub">SaleFish vs 6 competitors across 15 core capabilities. ✓ = Full · ~ = Partial/Limited · ✗ = Not Available</div>
  </div>

  <div class="card matrix-wrap" style="padding:0;overflow:hidden;">
    <table class="matrix-table">
      <thead>
        <tr>
          <th>Feature / Capability</th>
          <th class="th-salefish">SaleFish</th>
          <th>Blackline</th>
          <th>Constellation</th>
          <th>Lone Wolf</th>
          <th>Avesdo</th>
          <th>Spark RE</th>
          <th>Aareas</th>
        </tr>
      </thead>
      <tbody>
        <tr><td>Online Sales / Buyer Portal</td><td class="check-yes">✓</td><td class="check-yes">✓</td><td class="check-partial">~</td><td class="check-partial">~</td><td class="check-yes">✓</td><td class="check-yes">✓</td><td class="check-partial">~</td></tr>
        <tr><td>Deposit / Payment Management</td><td class="check-yes">✓</td><td class="check-partial">~</td><td class="check-partial">~</td><td class="check-no">✗</td><td class="check-yes">✓</td><td class="check-no">✗</td><td class="check-no">✗</td></tr>
        <tr><td>Pre-Authorized Debit (PAD)</td><td class="check-yes">✓</td><td class="check-no">✗</td><td class="check-no">✗</td><td class="check-no">✗</td><td class="check-partial">~</td><td class="check-no">✗</td><td class="check-no">✗</td></tr>
        <tr><td>CRM / Lead Management</td><td class="check-yes">✓</td><td class="check-partial">~</td><td class="check-yes">✓</td><td class="check-yes">✓</td><td class="check-yes">✓</td><td class="check-yes">✓</td><td class="check-no">✗</td></tr>
        <tr><td>Worksheet / Reservation Mgmt</td><td class="check-yes">✓</td><td class="check-yes">✓</td><td class="check-partial">~</td><td class="check-partial">~</td><td class="check-yes">✓</td><td class="check-partial">~</td><td class="check-no">✗</td></tr>
        <tr><td>Contract / APS Generation</td><td class="check-yes">✓</td><td class="check-partial">~</td><td class="check-yes">✓</td><td class="check-yes">✓</td><td class="check-yes">✓</td><td class="check-no">✗</td><td class="check-no">✗</td></tr>
        <tr><td>Digital / E-Signatures</td><td class="check-yes">✓</td><td class="check-partial">~</td><td class="check-yes">✓</td><td class="check-yes">✓</td><td class="check-yes">✓</td><td class="check-partial">~</td><td class="check-no">✗</td></tr>
        <tr><td>Inventory / Unit Allocation Mgmt</td><td class="check-yes">✓</td><td class="check-yes">✓</td><td class="check-partial">~</td><td class="check-no">✗</td><td class="check-yes">✓</td><td class="check-partial">~</td><td class="check-partial">~</td></tr>
        <tr><td>Interactive Sales Center / Site Map</td><td class="check-partial">~</td><td class="check-no">✗</td><td class="check-no">✗</td><td class="check-no">✗</td><td class="check-no">✗</td><td class="check-yes">✓</td><td class="check-yes">✓</td></tr>
        <tr><td>Analytics / Reporting Dashboard</td><td class="check-yes">✓</td><td class="check-partial">~</td><td class="check-yes">✓</td><td class="check-yes">✓</td><td class="check-partial">~</td><td class="check-yes">✓</td><td class="check-no">✗</td></tr>
        <tr><td>Mobile App (Native)</td><td class="check-yes">✓</td><td class="check-partial">~</td><td class="check-partial">~</td><td class="check-yes">✓</td><td class="check-partial">~</td><td class="check-yes">✓</td><td class="check-no">✗</td></tr>
        <tr><td>API / Third-Party Integrations</td><td class="check-yes">✓</td><td class="check-partial">~</td><td class="check-yes">✓</td><td class="check-yes">✓</td><td class="check-partial">~</td><td class="check-yes">✓</td><td class="check-no">✗</td></tr>
        <tr><td>Virtual Tours / 3D Visualization</td><td class="check-no">✗</td><td class="check-no">✗</td><td class="check-no">✗</td><td class="check-no">✗</td><td class="check-no">✗</td><td class="check-partial">~</td><td class="check-yes">✓</td></tr>
        <tr><td>Pricing Engine / Dynamic Pricing</td><td class="check-yes">✓</td><td class="check-partial">~</td><td class="check-partial">~</td><td class="check-no">✗</td><td class="check-partial">~</td><td class="check-no">✗</td><td class="check-no">✗</td></tr>
        <tr><td>New Home / Presale Specialization</td><td class="check-yes">✓</td><td class="check-yes">✓</td><td class="check-partial">~</td><td class="check-partial">~</td><td class="check-yes">✓</td><td class="check-yes">✓</td><td class="check-yes">✓</td></tr>
      </tbody>
    </table>
  </div>

  <div style="margin-top:20px;" class="grid-3">
    <div class="card">
      <div class="card-title">SaleFish Unique Advantages</div>
      <div class="news-item">Pre-authorized debit (PAD) deposit payments</div>
      <div class="news-item">End-to-end new home presale specialization</div>
      <div class="news-item">Canadian market-native compliance & workflows</div>
      <div class="news-item">Integrated pricing engine + inventory allocation</div>
    </div>
    <div class="card">
      <div class="card-title">Capability Gaps to Watch</div>
      <div class="news-item">Virtual tours / 3D visualization (Aareas leads)</div>
      <div class="news-item">Full interactive sales center hardware (Aareas)</div>
      <div class="news-item">Brokerage-side tools (Lone Wolf leads)</div>
      <div class="news-item">Enterprise CRM depth (Constellation leads)</div>
    </div>
    <div class="card">
      <div class="card-title">Competitive Moat Score</div>
      <div class="stat-row"><span class="stat-key">Deposit / PAD</span><span class="stat-val badge badge-green">Strong</span></div>
      <div class="stat-row"><span class="stat-key">Presale Workflow</span><span class="stat-val badge badge-green">Strong</span></div>
      <div class="stat-row"><span class="stat-key">Visual / Tours</span><span class="stat-val badge badge-amber">Weak</span></div>
      <div class="stat-row"><span class="stat-key">Scale / Integrations</span><span class="stat-val badge badge-amber">Medium</span></div>
    </div>
  </div>
</div>

<!-- ========== TAB 4: PROPTECH MARKET ========== -->
<div id="tab-market" class="tab-panel">
  <div class="section-header">
    <div class="section-title">Global PropTech Market</div>
    <div class="section-sub">Market size, investment trends, and sector growth drivers as of early 2026</div>
  </div>

  <div class="grid-4" style="margin-bottom:24px;">
    <div class="card market-stat-card">
      <div class="big-num">$45B</div>
      <div class="label">Global PropTech Market Size 2025</div>
    </div>
    <div class="card market-stat-card">
      <div class="big-num" style="color:var(--green);">16.1%</div>
      <div class="label">CAGR 2026–2035 (Precedence Research)</div>
    </div>
    <div class="card market-stat-card">
      <div class="big-num">$53–$55B</div>
      <div class="label">2026 Market Projection (multiple analyst estimates)</div>
    </div>
    <div class="card market-stat-card">
      <div class="big-num" style="color:var(--green);">$185B+</div>
      <div class="label">2034–35 Market Target</div>
    </div>
  </div>

  <div class="grid-2" style="margin-bottom:24px;">
    <div class="card">
      <div class="card-title">Market Size Trajectory</div>
      <div style="margin-top:12px;">
        <div class="stat-row"><span class="stat-key">2023</span><span class="stat-val">$32.0B</span></div>
        <div class="stat-row"><span class="stat-key">2024</span><span class="stat-val">$39.5B</span></div>
        <div class="stat-row"><span class="stat-key">2025</span><span class="stat-val" style="color:var(--accent-light);font-weight:700;">$45.2B (actual; VC investment up 67.9% YoY to $16.7B)</span></div>
        <div class="stat-row"><span class="stat-key">2026 (Projected)</span><span class="stat-val" style="color:var(--green);">$53–$55B (Precedence/Fortune/Market.us consensus)</span></div>
        <div class="stat-row"><span class="stat-key">2034–35 (Target)</span><span class="stat-val" style="color:var(--green);">$178–$185B+</span></div>
      </div>
    </div>
    <div class="card">
      <div class="card-title">Key Investment Trends 2026</div>
      <div style="margin-top:12px;">
        <div class="stat-row"><span class="stat-key">CAGR</span><span class="stat-val" style="color:var(--green);">14.6–16.1% (analyst range)</span></div>
        <div class="stat-row"><span class="stat-key">Key Driver</span><span class="stat-val">AI-integrated & infrastructure-scale platforms</span></div>
        <div class="stat-row"><span class="stat-key">Top Segment</span><span class="stat-val">AI & Agentic Automation</span></div>
        <div class="stat-row"><span class="stat-key">2nd Segment</span><span class="stat-val">Embedded Fintech / Transaction Platforms</span></div>
        <div class="stat-row"><span class="stat-key">3rd Segment</span><span class="stat-val">Sustainability / Green Tech</span></div>
        <div class="stat-row"><span class="stat-key">AI PropTech Growth</span><span class="stat-val" style="color:var(--green);">+42% annualized in 2025 — nearly double the 24% rate for non-AI PropTech</span></div>
        <div class="stat-row"><span class="stat-key">VC Investment Full-Year 2025</span><span class="stat-val" style="color:var(--green);">$16.7B — up 67.9% YoY</span></div>
        <div class="stat-row"><span class="stat-key">Jan 2026 Funding</span><span class="stat-val" style="color:var(--green);">$1.7B across ~50 deals — up 176% vs Jan 2025 (avg deal size $34M, up from $12.8M)</span></div>
        <div class="stat-row"><span class="stat-key">Feb 2026 Funding</span><span class="stat-val" style="color:var(--green);">$1.04B across 38 transactions (CRETI)</span></div>
      </div>
    </div>
  </div>

  <div class="section-header">
    <div class="section-title">Notable PropTech Funding & Activity</div>
  </div>
  <div class="card" style="padding:0;overflow:hidden;">
    <table class="inv-table">
      <thead>
        <tr><th>Company</th><th>Activity</th><th>Value</th><th>Segment</th><th>Relevance to SaleFish</th></tr>
      </thead>
      <tbody>
        <tr><td><strong>Kiavi</strong></td><td>Debt Financing (Q1 2026)</td><td>$350M</td><td>Residential Lending</td><td>Low — lending, not sales tech</td></tr>
        <tr><td><strong>Bedrock Robotics</strong></td><td>Equity Round (Q1 2026)</td><td>$270M</td><td>AI / Construction Robotics</td><td>Low — construction phase automation</td></tr>
        <tr><td><strong>Metiundo</strong></td><td>Series A (Q1 2026)</td><td>$47.6M</td><td>CRE Data / AI</td><td>Medium — AI data analytics for RE</td></tr>
        <tr><td><strong>OneDome</strong></td><td>Series C (Q1 2026)</td><td>$34.2M</td><td>Property Transactions</td><td>Medium — digital transaction platform</td></tr>
        <tr><td><strong>Stake</strong></td><td>Series B (Q1 2026)</td><td>$31M</td><td>Multifamily Fintech</td><td>High — embedded fintech / PropPay LX adjacent</td></tr>
        <tr><td><strong>Ownwell</strong></td><td>Series B (Q1 2026)</td><td>$30M</td><td>Property Tax Tech</td><td>Low — property tax, not sales</td></tr>
        <tr><td><strong>Fifth Wall</strong></td><td>Fund Close (Q1 2026)</td><td>$414M</td><td>Residential PropTech VC</td><td>High — $414M REACT + flagship fund back to residential proptech; backed Breezy (AI-native agent workflow)</td></tr>
      </tbody>
    </table>
  </div>

  <div style="margin-top:24px;" class="grid-3">
    <div class="card">
      <div class="card-title">Top Growth Drivers</div>
      <div class="news-item">AI and machine learning integration across entire RE workflow</div>
      <div class="news-item">Digital-first buyer expectations post-pandemic</div>
      <div class="news-item">Embedded fintech adoption in real estate transactions</div>
      <div class="news-item">Regulatory pressure for digitized documentation</div>
    </div>
    <div class="card">
      <div class="card-title">Key Risks & Headwinds</div>
      <div class="news-item">Rising interest rates slowing transaction volumes globally</div>
      <div class="news-item">Market consolidation reducing mid-tier vendor viability</div>
      <div class="news-item">Data privacy regulations increasing compliance costs</div>
      <div class="news-item">Large tech entrants (Salesforce, SAP) eyeing RE vertical</div>
    </div>
    <div class="card">
      <div class="card-title">Canadian PropTech Landscape 2025</div>
      <div class="news-item">C$450M raised in 2025 — down from C$800M in 2024; investors more selective, demanding earlier traction</div>
      <div class="news-item">590+ active Canadian PropTech startups (up from 535); 41% residential, 38% commercial, 21% construction</div>
      <div class="news-item">Build Canada Homes: C$13B+ federal initiative catalyzing PropTech adoption in new home construction</div>
      <div class="news-item">AI regulation advancing in 2026 — data privacy, algorithmic transparency, IP ownership are key fronts</div>
    </div>
    <div class="card">
      <div class="card-title">Opportunity Areas for SaleFish</div>
      <div class="news-item">PropPay LX deposit payments — embedded fintech tailwind</div>
      <div class="news-item">AI-enhanced pricing and inventory optimization features</div>
      <div class="news-item">API ecosystem to integrate with new home developer tech stacks</div>
      <div class="news-item">International expansion into US new home presale market</div>
    </div>
  </div>
</div>

<!-- ========== FEATURE SUGGESTIONS ========== -->
<div id="tab-features" class="tab-panel">
  <div class="section-header">
    <div class="section-title">Feature Suggestions — Competitive Gap Analysis</div>
    <div class="section-sub">What agents and builders are asking for, mapped against SaleFish and competitor capabilities (Q1 2026 industry data)</div>
  </div>

  <!-- TOP SUMMARY -->
  <div class="grid-3" style="margin-bottom:24px;">
    <div class="card market-stat-card">
      <div class="big-num">18</div>
      <div class="label">Features Analyzed</div>
    </div>
    <div class="card market-stat-card">
      <div class="big-num" style="color:var(--green);">8</div>
      <div class="label">Features Where SaleFish Leads</div>
    </div>
    <div class="card market-stat-card">
      <div class="big-num" style="color:var(--red);">5</div>
      <div class="label">Priority Gaps to Close</div>
    </div>
  </div>

  <!-- GAP ANALYSIS TABLE -->
  <div class="card" style="padding:0;overflow:hidden;margin-bottom:24px;">
    <div style="padding:16px 20px;border-bottom:1px solid var(--border-subtle);">
      <div class="card-title">Competitive Gap Analysis</div>
    </div>
    <div style="overflow-x:auto;">
      <table class="matrix-table">
        <thead>
          <tr>
            <th style="text-align:left;min-width:200px;">Feature / Capability</th>
            <th>Market Demand</th>
            <th>Who's Asking</th>
            <th>SaleFish Today</th>
            <th>Competitor Status</th>
            <th>Priority</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td style="text-align:left;font-weight:500;">AI-Powered Dynamic Pricing</td>
            <td><span class="badge badge-red">HIGH</span></td>
            <td>Builders / Developers</td>
            <td><span class="badge badge-amber">PARTIAL</span></td>
            <td>No competitor has this for presales; Lone Wolf exploring for resale</td>
            <td><span class="badge badge-red">CRITICAL</span></td>
          </tr>
          <tr>
            <td style="text-align:left;font-weight:500;">Agentic AI Workflow Automation</td>
            <td><span class="badge badge-red">HIGH</span></td>
            <td>All</td>
            <td><span class="badge badge-red">GAP</span></td>
            <td>Lone Wolf embedding AI across suite; Reelly AI generating presentations</td>
            <td><span class="badge badge-red">CRITICAL</span></td>
          </tr>
          <tr>
            <td style="text-align:left;font-weight:500;">Buyer Propensity / Lead Scoring</td>
            <td><span class="badge badge-red">HIGH</span></td>
            <td>Agents / Builders</td>
            <td><span class="badge badge-red">GAP</span></td>
            <td>Spark has "Smart Groups" segmentation; Lone Wolf exploring</td>
            <td><span class="badge badge-red">CRITICAL</span></td>
          </tr>
          <tr>
            <td style="text-align:left;font-weight:500;">3D Virtual Tours / VR Integration</td>
            <td><span class="badge badge-red">HIGH</span></td>
            <td>Builders / Buyers</td>
            <td><span class="badge badge-red">GAP</span></td>
            <td>Aareas leads (Virtual Design Center); Matterport ecosystem</td>
            <td><span class="badge badge-amber">HIGH</span></td>
          </tr>
          <tr>
            <td style="text-align:left;font-weight:500;">Interactive Digital Sales Center</td>
            <td><span class="badge badge-red">HIGH</span></td>
            <td>Builders</td>
            <td><span class="badge badge-amber">PARTIAL</span></td>
            <td>Aareas, Spark lead; Blackline Cast for screen casting</td>
            <td><span class="badge badge-amber">HIGH</span></td>
          </tr>
          <tr>
            <td style="text-align:left;font-weight:500;">Embedded Deposit Payments (PAD)</td>
            <td><span class="badge badge-red">HIGH</span></td>
            <td>Builders / Developers</td>
            <td><span class="badge badge-green">LEADS</span></td>
            <td>Avesdo + VoPay (partial); no other competitor has PAD</td>
            <td><span class="badge badge-green">MAINTAIN LEAD</span></td>
          </tr>
          <tr>
            <td style="text-align:left;font-weight:500;">Phase Release Optimization</td>
            <td><span class="badge badge-amber">MEDIUM</span></td>
            <td>Developers</td>
            <td><span class="badge badge-amber">PARTIAL</span></td>
            <td>No competitor offers AI-driven phase release; manual in all platforms</td>
            <td><span class="badge badge-red">CRITICAL</span></td>
          </tr>
          <tr>
            <td style="text-align:left;font-weight:500;">Cross-Border Sales / Multi-Currency</td>
            <td><span class="badge badge-amber">MEDIUM</span></td>
            <td>Developers / Agents</td>
            <td><span class="badge badge-red">GAP</span></td>
            <td>Reelly AI (UAE), IQI Global (SE Asia) have cross-border; FastKey had it</td>
            <td><span class="badge badge-amber">HIGH</span></td>
          </tr>
          <tr>
            <td style="text-align:left;font-weight:500;">Real-Time Inventory / Live Unit Status</td>
            <td><span class="badge badge-red">HIGH</span></td>
            <td>Agents / Builders</td>
            <td><span class="badge badge-green">LEADS</span></td>
            <td>Avesdo and Blackline have basic; SaleFish most mature</td>
            <td><span class="badge badge-green">MAINTAIN LEAD</span></td>
          </tr>
          <tr>
            <td style="text-align:left;font-weight:500;">Automated APS / Contract Generation</td>
            <td><span class="badge badge-red">HIGH</span></td>
            <td>Builders / Developers</td>
            <td><span class="badge badge-green">LEADS</span></td>
            <td>Avesdo, Lone Wolf have; Blackline partial</td>
            <td><span class="badge badge-green">MAINTAIN LEAD</span></td>
          </tr>
          <tr>
            <td style="text-align:left;font-weight:500;">Post-Sale / Warranty Management</td>
            <td><span class="badge badge-amber">MEDIUM</span></td>
            <td>Builders</td>
            <td><span class="badge badge-red">GAP</span></td>
            <td>Spark acquired Juniper for this; Housebuilder Pro has aftercare</td>
            <td><span class="badge badge-amber">HIGH</span></td>
          </tr>
          <tr>
            <td style="text-align:left;font-weight:500;">Digital Earnest Money / Escrow</td>
            <td><span class="badge badge-amber">MEDIUM</span></td>
            <td>Builders / Buyers</td>
            <td><span class="badge badge-amber">PARTIAL</span></td>
            <td>Emerging in US market; no competitor dominates</td>
            <td><span class="badge badge-amber">HIGH</span></td>
          </tr>
          <tr>
            <td style="text-align:left;font-weight:500;">Sustainability / Green Certification Tracking</td>
            <td><span class="badge badge-amber">MEDIUM</span></td>
            <td>Builders / Regulators</td>
            <td><span class="badge badge-red">GAP</span></td>
            <td>No competitor offers — emerging requirement from OHBA/CHBA</td>
            <td><span class="badge badge-blue">MEDIUM</span></td>
          </tr>
          <tr>
            <td style="text-align:left;font-weight:500;">Mobile-Native Agent App</td>
            <td><span class="badge badge-red">HIGH</span></td>
            <td>Agents</td>
            <td><span class="badge badge-green">LEADS</span></td>
            <td>Lone Wolf, Spark have native apps; Blackline partial</td>
            <td><span class="badge badge-green">MAINTAIN LEAD</span></td>
          </tr>
          <tr>
            <td style="text-align:left;font-weight:500;">Open API / Integration Ecosystem</td>
            <td><span class="badge badge-red">HIGH</span></td>
            <td>All</td>
            <td><span class="badge badge-amber">PARTIAL</span></td>
            <td>Lone Wolf launched API Portal (Feb 2026); Constellation deep integrations</td>
            <td><span class="badge badge-amber">HIGH</span></td>
          </tr>
          <tr>
            <td style="text-align:left;font-weight:500;">Automated Marketing / Email Campaigns</td>
            <td><span class="badge badge-amber">MEDIUM</span></td>
            <td>Agents / Builders</td>
            <td><span class="badge badge-red">GAP</span></td>
            <td>Spark leads with marketing automation; Runway has this</td>
            <td><span class="badge badge-blue">MEDIUM</span></td>
          </tr>
          <tr>
            <td style="text-align:left;font-weight:500;">Blockchain / Tokenized Transactions</td>
            <td><span class="badge badge-blue">LOW</span></td>
            <td>Developers (Dubai)</td>
            <td><span class="badge badge-red">GAP</span></td>
            <td>Reelly AI + Tether collaboration; Dubai leading adoption</td>
            <td><span class="badge badge-blue">WATCH</span></td>
          </tr>
          <tr>
            <td style="text-align:left;font-weight:500;">Fractional Ownership Support</td>
            <td><span class="badge badge-blue">LOW</span></td>
            <td>Developers / Investors</td>
            <td><span class="badge badge-red">GAP</span></td>
            <td>Nawy (Shares), Stake ($31M Series B) — primarily UAE/emerging markets</td>
            <td><span class="badge badge-blue">WATCH</span></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- BOTTOM CARDS -->
  <div class="grid-3">
    <div class="card">
      <div class="card-title">🏗️ Top Builder Asks (NAHB/CHBA/IBS 2026)</div>
      <div class="news-item">AI-powered pricing recommendations that adapt to market conditions in real-time</div>
      <div class="news-item">Phase release optimization — when to release which units at what price</div>
      <div class="news-item">3D/VR visualization integrated into the sales workflow, not a separate tool</div>
      <div class="news-item">Automated compliance documentation (Tarion, NHBRC, warranty)</div>
      <div class="news-item">Post-sale/warranty management connected to the original sales record</div>
      <div class="news-item">Real-time analytics dashboard with sales velocity and absorption tracking</div>
    </div>
    <div class="card">
      <div class="card-title">👤 Top Agent Asks (NAR Tech Survey 2026)</div>
      <div class="news-item">Mobile-first tools — manage reservations, worksheets, and deposits from phone</div>
      <div class="news-item">Lead scoring that tells them which buyers are most likely to convert</div>
      <div class="news-item">Instant digital contract generation and e-signing without switching platforms</div>
      <div class="news-item">Cross-border sales support — international buyer KYC, multi-currency pricing</div>
      <div class="news-item">AI-generated property descriptions and marketing content from unit data</div>
      <div class="news-item">Live unit availability across multiple projects on a single dashboard</div>
    </div>
    <div class="card">
      <div class="card-title">⚡ SaleFish Quick Wins</div>
      <div class="news-item"><strong>AI pricing recommendations:</strong> HIGH demand, no competitor has it for presales, SaleFish already has pricing data</div>
      <div class="news-item"><strong>Buyer lead scoring:</strong> HIGH demand, Spark partial, can leverage existing CRM data</div>
      <div class="news-item"><strong>Phase release optimizer:</strong> MEDIUM demand, zero competition, unique SaleFish data advantage</div>
      <div class="news-item"><strong>VR/3D partnership:</strong> HIGH demand, partner (don't build) with Matterport or EnvisionVR</div>
      <div class="news-item"><strong>Open API documentation:</strong> HIGH demand, Lone Wolf just launched theirs, SaleFish needs parity</div>
    </div>
  </div>

</div>

<!-- ========== TAB 5: TECH TRENDS ========== -->
<div id="tab-trends" class="tab-panel">
  <div class="section-header">
    <div class="section-title">Technology Trends Shaping PropTech</div>
    <div class="section-sub">Eight key technology vectors driving transformation in new home sales and real estate software</div>
  </div>

  <div class="grid-2">

    <div class="card trend-card">
      <div class="trend-icon">🤖</div>
      <div class="trend-name">Agentic AI</div>
      <div class="trend-desc">AI with autonomous decision-making now in production in 2026 — no longer a pilot. 90%+ of leading RE firms consider AI a strategic priority; 60%+ have active deployments. AI-powered AVMs now achieve 2.8% median error rate (down from 10–15% five years ago). Agentic AI is the #1 PropTech trend per NAR Tech & Innovation.</div>
      <div class="trend-bar-wrap"><div class="trend-bar" style="width:78%;background:linear-gradient(90deg,var(--accent),#7FBF8E);"></div></div>
      <div class="trend-bar-label"><span>Adoption in PropTech</span><span style="color:var(--accent-light);font-weight:600;">78% active/piloting (up from 72%)</span></div>
      <div style="margin-top:12px;"><span class="badge badge-red">HIGH PRIORITY for SaleFish</span></div>
    </div>

    <div class="card trend-card">
      <div class="trend-icon">📊</div>
      <div class="trend-name">Predictive Analytics</div>
      <div class="trend-desc">Data-driven pricing, demand forecasting, and buyer propensity scoring. Builders using predictive models to optimize unit pricing, phase release timing, and sales velocity. Core competitive differentiator in 2026.</div>
      <div class="trend-bar-wrap"><div class="trend-bar" style="width:55%;background:linear-gradient(90deg,#5B9A6F,#7FBF8E);"></div></div>
      <div class="trend-bar-label"><span>Adoption in PropTech</span><span style="color:#7FBF8E;font-weight:600;">55% active users</span></div>
      <div style="margin-top:12px;"><span class="badge badge-red">HIGH PRIORITY for SaleFish</span></div>
    </div>

    <div class="card trend-card">
      <div class="trend-icon">🥽</div>
      <div class="trend-name">VR/AR Tours & Visualization</div>
      <div class="trend-desc">2026 forecast as the year immersive tech fulfills its promise — voice-driven AI with environmental awareness, seamless digital/real-world integration. Virtual design centers and photorealistic home tours now table-stakes for presale marketing.</div>
      <div class="trend-bar-wrap"><div class="trend-bar" style="width:56%;background:linear-gradient(90deg,#0D9488,#14B8A6);"></div></div>
      <div class="trend-bar-label"><span>Adoption in PropTech</span><span style="color:#14B8A6;font-weight:600;">56% deployed</span></div>
      <div style="margin-top:12px;"><span class="badge badge-amber">PARTNERSHIP OPPORTUNITY</span></div>
    </div>

    <div class="card trend-card">
      <div class="trend-icon">🏗️</div>
      <div class="trend-name">IoT & Smart Buildings</div>
      <div class="trend-desc">Integrated building management, smart home features, and IoT connectivity becoming part of new home value propositions. Developers using IoT data for building performance and as a sales differentiator.</div>
      <div class="trend-bar-wrap"><div class="trend-bar" style="width:38%;background:linear-gradient(90deg,#059669,#10B981);"></div></div>
      <div class="trend-bar-label"><span>Adoption in PropTech</span><span style="color:#10B981;font-weight:600;">38% piloting</span></div>
      <div style="margin-top:12px;"><span class="badge badge-blue">MONITOR</span></div>
    </div>

    <div class="card trend-card">
      <div class="trend-icon">✍️</div>
      <div class="trend-name">Digital Signatures & e-Contracts</div>
      <div class="trend-desc">Fully digital APS (Agreement of Purchase and Sale) execution, electronic deposits, and remote closing are now expected. Regulation evolving to recognize digital originals in more provinces. Core workflow requirement.</div>
      <div class="trend-bar-wrap"><div class="trend-bar" style="width:82%;background:linear-gradient(90deg,var(--green),#34D399);"></div></div>
      <div class="trend-bar-label"><span>Adoption in PropTech</span><span style="color:var(--green);font-weight:600;">82% standard</span></div>
      <div style="margin-top:12px;"><span class="badge badge-green">ALREADY STRONG — Maintain</span></div>
    </div>

    <div class="card trend-card">
      <div class="trend-icon">⛓️</div>
      <div class="trend-name">Blockchain & Tokenization</div>
      <div class="trend-desc">AI-enhanced smart contracts on blockchain enabling adaptive, self-executing agreements. Tokenized real-world assets hit ~$24B in 2025; RWA tokenization projected to reach $16T by 2030. Fractional ownership and title tokenization gaining traction in select markets. Still primarily speculative for Canadian new home sales.</div>
      <div class="trend-bar-wrap"><div class="trend-bar" style="width:24%;background:linear-gradient(90deg,#D97706,#F59E0B);"></div></div>
      <div class="trend-bar-label"><span>Adoption in PropTech</span><span style="color:#F59E0B;font-weight:600;">24% exploring</span></div>
      <div style="margin-top:12px;"><span class="badge badge-blue">WATCH — Long Term</span></div>
    </div>

    <div class="card trend-card">
      <div class="trend-icon">🌱</div>
      <div class="trend-name">Sustainability Tech</div>
      <div class="trend-desc">Green certification tracking, energy performance disclosure, and ESG reporting tools. Canadian regulations (OHBA, CHBA Net Zero) requiring builders to document and communicate sustainability features during presale.</div>
      <div class="trend-bar-wrap"><div class="trend-bar" style="width:44%;background:linear-gradient(90deg,#16A34A,#22C55E);"></div></div>
      <div class="trend-bar-label"><span>Adoption in PropTech</span><span style="color:#22C55E;font-weight:600;">44% investing</span></div>
      <div style="margin-top:12px;"><span class="badge badge-amber">FUTURE FEATURE OPPORTUNITY</span></div>
    </div>

    <div class="card trend-card">
      <div class="trend-icon">💳</div>
      <div class="trend-name">Embedded FinTech</div>
      <div class="trend-desc">Payment processing embedded directly into sales platforms (e.g., Avesdo + VoPay partnership). PropPay LX is directly in this trend. Global embedded finance transaction volumes grew from $2.6T (2021) to $5.8T (2025) — projected $7T+ in 2026. Real Wallet won the 2026 FinTech Breakthrough Award for embedded finance in residential RE. Critical competitive battleground in 2026.</div>
      <div class="trend-bar-wrap"><div class="trend-bar" style="width:68%;background:linear-gradient(90deg,#DB2777,#EC4899);"></div></div>
      <div class="trend-bar-label"><span>Adoption in PropTech</span><span style="color:#EC4899;font-weight:600;">68% integrating (up from 65%)</span></div>
      <div style="margin-top:12px;"><span class="badge badge-red">CORE ADVANTAGE — PropPay LX</span></div>
    </div>

  </div>
</div>

<!-- ========== TAB 6: CANADIAN OUTLOOK ========== -->
<div id="tab-canada" class="tab-panel">
  <div class="section-header">
    <div class="section-title">Canadian Real Estate Outlook</div>
    <div class="section-sub">Regional market data, sales forecasts, and price trends — Q1 2026</div>
  </div>

  <div class="grid-4" style="margin-bottom:24px;">
    <div class="card market-stat-card">
      <div class="big-num">2.25%</div>
      <div class="label">Bank of Canada Policy Rate (held Mar 18 2026 — next Apr 29; 96.5% probability of hold)</div>
    </div>
    <div class="card market-stat-card">
      <div class="big-num">$664K</div>
      <div class="label">National Avg Home Price (Feb 2026 actual $663,828, ↓0.2% YoY; HPI ↓4.8% YoY — March CREA data due Apr 16)</div>
    </div>
    <div class="card market-stat-card">
      <div class="big-num" style="color:var(--green);">~496K</div>
      <div class="label">2026 Forecast Transactions (CREA revised — largest between-forecast downgrade since 2008–09)</div>
    </div>
    <div class="card market-stat-card">
      <div class="big-num" style="color:var(--amber);">+2.9%</div>
      <div class="label">Transaction Volume YoY Growth (revised down from +5.1%; 2025 base also cut)</div>
    </div>
  </div>

  <div class="grid-2" style="margin-bottom:24px;">

    <div class="card region-card">
      <div class="region-city">Greater Toronto Area</div>
      <div class="region-province">Ontario · SaleFish Home Market</div>
      <div class="region-price">$1,017,796</div>
      <div class="region-change change-down">↓ 6.7% YoY avg; HPI benchmark ↓7.4% YoY (Mar 2026 TRREB, released Apr 7 2026)</div>
      <div style="height:14px;"></div>
      <div class="stat-row"><span class="stat-key">Market Status</span><span class="stat-val badge badge-amber">Softening</span></div>
      <div class="stat-row"><span class="stat-key">Mar 2026 Sales</span><span class="stat-val">5,039 — up 1.7% YoY (slight improvement); new listings ↓16.7% YoY (supply tightening)</span></div>
      <div class="stat-row"><span class="stat-key">Interest Rate Impact</span><span class="stat-val">BoC held 2.25% Mar 18 — next decision Apr 29 (96.5% probability of hold)</span></div>
      <div class="stat-row"><span class="stat-key">Builder Activity</span><span class="stat-val">Selective launches, smaller phases; elevated condo inventory</span></div>
      <div class="stat-row"><span class="stat-key">SaleFish Opportunity</span><span class="stat-val badge badge-green">HIGH — home market</span></div>
    </div>

    <div class="card region-card">
      <div class="region-city">Greater Vancouver</div>
      <div class="region-province">British Columbia · Avesdo Territory</div>
      <div class="region-price">$1,104,300</div>
      <div class="region-change change-down">↓ 6.8% YoY; +0.4% MoM (Mar 2026 composite benchmark, Greater Vancouver Realtors)</div>
      <div style="height:14px;"></div>
      <div class="stat-row"><span class="stat-key">Market Status</span><span class="stat-val badge badge-amber">Adjusting</span></div>
      <div class="stat-row"><span class="stat-key">Mar 2026 Sales</span><span class="stat-val">2,032 — ↓2.8% YoY; 31.8% below 10-year seasonal avg — buyers & sellers in wait-and-see mode</span></div>
      <div class="stat-row"><span class="stat-key">Interest Rate Impact</span><span class="stat-val">Still affordability-constrained; detached benchmark $1,854,800 (↓8.2% YoY)</span></div>
      <div class="stat-row"><span class="stat-key">Builder Activity</span><span class="stat-val">Slower resale, prices not moving significantly in either direction</span></div>
      <div class="stat-row"><span class="stat-key">SaleFish Opportunity</span><span class="stat-val badge badge-blue">MEDIUM</span></div>
    </div>

    <div class="card region-card">
      <div class="region-city">Calgary</div>
      <div class="region-province">Alberta · Growth Market</div>
      <div class="region-price">$641,844</div>
      <div class="region-change change-up">↑ 0.4% YoY avg; +2.2% MoM (Mar 2026, CREB) — benchmark ↓4.2% YoY</div>
      <div style="height:14px;"></div>
      <div class="stat-row"><span class="stat-key">Market Status</span><span class="stat-val badge badge-green">Resilient</span></div>
      <div class="stat-row"><span class="stat-key">Mar 2026 Sales</span><span class="stat-val">1,881 — ↓12.9% YoY; sales-to-new-listings 55% (seller's market territory for first time since early 2025)</span></div>
      <div class="stat-row"><span class="stat-key">Interest Rate Impact</span><span class="stat-val">Most affordable major market nationally; detached $808,924, condos $344,063</span></div>
      <div class="stat-row"><span class="stat-key">Builder Activity</span><span class="stat-val">Tightening supply supporting prices; still strongest major market nationally</span></div>
      <div class="stat-row"><span class="stat-key">SaleFish Opportunity</span><span class="stat-val badge badge-green">HIGH — expansion target</span></div>
    </div>

    <div class="card region-card">
      <div class="region-city">Montreal / Ottawa</div>
      <div class="region-province">Quebec & Ontario East</div>
      <div class="region-price">$656,708 (MTL avg)</div>
      <div class="region-change change-up">↑ Montreal +6.1% YoY (Feb 2026 avg, WOWA/CREA)</div>
      <div style="height:14px;"></div>
      <div class="stat-row"><span class="stat-key">Market Status</span><span class="stat-val badge badge-green">Strong Growth</span></div>
      <div class="stat-row"><span class="stat-key">Ottawa (Feb 2026)</span><span class="stat-val">$662,773 avg / $615,400 benchmark — ↓1.1% YoY</span></div>
      <div class="stat-row"><span class="stat-key">Q4 2026 Forecast</span><span class="stat-val" style="color:var(--green);">▲ MTL leading national growth; March data releases Apr 16 CREA</span></div>
      <div class="stat-row"><span class="stat-key">Interest Rate Impact</span><span class="stat-val">Positive — rate cuts stimulating demand</span></div>
      <div class="stat-row"><span class="stat-key">Builder Activity</span><span class="stat-val">Montreal seeing increased condo launches</span></div>
      <div class="stat-row"><span class="stat-key">SaleFish Opportunity</span><span class="stat-val badge badge-amber">MEDIUM — consider bilingual push</span></div>
    </div>
  </div>

  <div class="card">
    <div class="card-title">2026 Canadian Market Outlook — Key Themes</div>
    <div class="grid-3" style="margin-top:12px;">
      <div>
        <div class="comp-section-title">Macro Tailwinds</div>
        <div class="news-item">BoC rate held at 2.25% (Mar 18 2026) — next decision Apr 29; 96.5% probability of hold (Polymarket)</div>
        <div class="news-item">Pent-up demand from first-time buyers shut out over past 4 years (CREA)</div>
        <div class="news-item">CREA revised forecast ~496K transactions (+2.9%) — largest between-forecast downgrade on record; Mar 2026 CREA data releases Apr 16</div>
        <div class="news-item">Build Canada Homes (BCH) — new federal agency (Sep 2025) deploying C$13B+ to catalyze public-private homebuilding; tailwind for new home tech adoption</div>
      </div>
      <div>
        <div class="comp-section-title">Macro Headwinds</div>
        <div class="news-item">US tariffs caused CREA to cut housing forecast — largest downgrade since 2008-09 financial crisis</div>
        <div class="news-item">Toronto benchmark ↓7.9% YoY, Vancouver ↓6.8% YoY (Feb 2026, WOWA/CREA)</div>
        <div class="news-item">Feb 2026 actual sales ↓7.8% YoY nationally — transaction volume headwind deeper than prices suggest</div>
        <div class="news-item">TD Economics (Mar 26 2026) revised 2026 forecast to ↓1.8% sales YoY and ↓0.3% prices nationally — a full reversal from their Dec 2025 outlook of +9.3% sales growth; tariff shock cited as primary driver. 2027 recovery forecast at +9.6% sales, +2.7% prices.</div>
        <div class="news-item">BoC Jan 2026 MPR projects GDP growth of 1.1% in 2026 — US tariffs estimated to leave CA GDP ~1.5% lower by end-2026 vs. pre-tariff baseline. Updated forecasts Apr 29 MPR.</div>
        <div class="news-item">Construction cost inflation and labour shortages limiting new supply</div>
      </div>
      <div>
        <div class="comp-section-title">Presale-Specific Signals</div>
        <div class="news-item">Early spring mixed: Toronto and Hamilton transactions recovering; Vancouver, Fraser Valley, Calgary, Edmonton still soft (RBC Economics, Apr 2026)</div>
        <div class="news-item">Montreal leading national price growth at +6.1% YoY — strong presale activity</div>
        <div class="news-item">Builders launching smaller phases, managing risk in softening markets</div>
        <div class="news-item">Digital-first presale tools increasingly expected — agentic AI moving into production</div>
        <div class="news-item">Apr 16 CREA March data release will be first full spring market read — key signal for Q2 builder sentiment</div>
      </div>
    </div>
  </div>
</div>

<!-- ========== GLOBAL MARKET OPPORTUNITIES ========== -->
<div id="tab-globalopps" class="tab-panel">
  <div class="section-header">
    <div class="section-title">Global Market Opportunities</div>
    <div class="section-sub">International markets with expansion potential for SaleFish — ranked by opportunity level</div>
  </div>

  <!-- TOP METRICS -->
  <div class="grid-4" style="margin-bottom:24px;">
    <div class="card market-stat-card">
      <div class="big-num" style="font-size:28px;">1.5M</div>
      <div class="label">🇬🇧 UK New Home Target (this Parliament)</div>
    </div>
    <div class="card market-stat-card">
      <div class="big-num" style="font-size:28px;">A$1.8B</div>
      <div class="label">🇦🇺 Australia PropTech Market 2025</div>
    </div>
    <div class="card market-stat-card">
      <div class="big-num" style="font-size:28px;color:var(--red);">FastKey†</div>
      <div class="label">🇸🇬 Singapore — Dominant Platform Shut Down</div>
    </div>
    <div class="card market-stat-card">
      <div class="big-num" style="font-size:28px;">70%</div>
      <div class="label">🇦🇪 Dubai Transactions That Are Off-Plan</div>
    </div>
  </div>

  <!-- REGION CARDS -->
  <div class="grid-2">

    <!-- SINGAPORE / SE ASIA -->
    <div class="card region-card">
      <div class="region-city">🇸🇬 Singapore / Southeast Asia</div>
      <div class="region-province">FastKey Vacuum · Immediate Opportunity</div>
      <div class="region-price" style="font-size:22px;">OPPORTUNITY: <span style="color:var(--red);">VERY HIGH</span></div>
      <div style="height:14px;"></div>
      <div class="stat-row"><span class="stat-key">Market Signal</span><span class="stat-val badge badge-red">FastKey shut down Oct 2024 — no SG replacement identified</span></div>
      <div class="stat-row"><span class="stat-key">2026 Pipeline (SG)</span><span class="stat-val">18 private residential + 5 EC projects — ~9,700+ units; OCR-dominant (64% of supply); major launches include Tengah Garden Residences (863 units), Bayshore Walk (815 units), Tengah Garden Ave (860 units)</span></div>
      <div class="stat-row"><span class="stat-key">Malaysia</span><span class="stat-val">MHub filling vacuum (RM 50B+ processed) — but SG & Indonesia still without dominant replacement</span></div>
      <div class="stat-row"><span class="stat-key">Pricing</span><span class="stat-val">S$1,800–3,200+ per sqft; developer demand for digital launch tools rising</span></div>
      <div class="stat-row"><span class="stat-key">Competitors</span><span class="stat-val">MHub (Malaysia only). Singapore: no dominant platform. IQI Global (agents) potential channel partner</span></div>
      <div class="stat-row"><span class="stat-key">SaleFish Fit</span><span class="stat-val badge badge-green">VERY HIGH — Singapore vacuum still open</span></div>
      <div style="height:10px;"></div>
      <div class="comp-section-title">Why Now</div>
      <div class="news-item">Singapore still has no dominant FastKey replacement — 500+ projects, 100+ developers remain underserved</div>
      <div class="news-item">Active 2026 new launch market: 18 private residential + 5 EC projects confirmed, driven by suburban OCR demand (64% of supply) and first batch of EC launches since 2025</div>
      <div class="news-item">English-speaking market with strong rule of law and PropTech infrastructure — low localization friction</div>
    </div>

    <!-- DUBAI / UAE -->
    <div class="card region-card">
      <div class="region-city">🇦🇪 Dubai / UAE</div>
      <div class="region-province">Off-Plan Capital of the World — Geopolitical Watch</div>
      <div class="region-price" style="font-size:22px;">OPPORTUNITY: <span style="color:var(--amber);">MEDIUM-HIGH ⚠️</span></div>
      <div style="height:14px;"></div>
      <div class="stat-row"><span class="stat-key">Off-Plan Share</span><span class="stat-val">~70% of all residential transactions</span></div>
      <div class="stat-row"><span class="stat-key">Q1 2026 Transactions</span><span class="stat-val" style="color:var(--green);">47,996 deals worth AED 176.7B ($48.1B) — +5.5% volume, +23.4% value YoY (record quarter despite conflict)</span></div>
      <div class="stat-row"><span class="stat-key">March Off-Plan</span><span class="stat-val" style="color:var(--amber);">↓21% MoM to 9,368 transactions — conflict-driven sentiment shift; YTD still up 15%</span></div>
      <div class="stat-row"><span class="stat-key">Geopolitical Risk</span><span class="stat-val" style="color:var(--red);">Feb 28: US-Israel strikes on Iran; Iran closed Strait of Hormuz; ceasefire brokered. Prices ↓4–7% (not catastrophic). Risk of 10–15% off-plan correction if conflict re-escalates.</span></div>
      <div class="stat-row"><span class="stat-key">PropTech Connect 2026</span><span class="stat-val">Hosted in Dubai — 4,000+ participants, 1,500+ companies; AI, blockchain & tokenization as top themes</span></div>
      <div class="stat-row"><span class="stat-key">Competitors</span><span class="stat-val">RealCube (NOVA AI launched), Reelly AI (60K+ agents) — neither dominates end-to-end presale</span></div>
      <div class="stat-row"><span class="stat-key">SaleFish Fit</span><span class="stat-val badge badge-amber">MEDIUM-HIGH — monitor geopolitical trajectory before committing</span></div>
      <div style="height:10px;"></div>
      <div class="comp-section-title">Why Now — With Caution</div>
      <div class="news-item">Record Q1 2026 — $48.1B in transactions, +23.4% value YoY despite conflict mid-quarter; market fundamentals remain strong</div>
      <div class="news-item">Developer payment plans (60/40, 80/20, post-handover) create complex deposit management needs — PropPay LX advantage remains intact</div>
      <div class="news-item">⚠️ Off-plan sentiment is the first to soften in geopolitical shocks — monitor ceasefire durability through Q2 before full entry commitment</div>
      <div class="news-item">PropTech Connect 2026 signals maturing ecosystem and government appetite for tech investment</div>
    </div>

    <!-- UNITED KINGDOM -->
    <div class="card region-card">
      <div class="region-city">🇬🇧 United Kingdom</div>
      <div class="region-province">Government Housing Mandate</div>
      <div class="region-price" style="font-size:22px;">OPPORTUNITY: <span style="color:var(--red);">HIGH</span></div>
      <div style="height:14px;"></div>
      <div class="stat-row"><span class="stat-key">Housing Target</span><span class="stat-val">1.5M new homes this Parliament (300K/year goal); ~309,600 delivered since Jul 2024</span></div>
      <div class="stat-row"><span class="stat-key">Q4 2025 Housing Starts</span><span class="stat-val" style="color:var(--green);">37,300 (seasonally adjusted) — up 23% QoQ and 24% YoY; "green shoots of recovery" (Housing Secretary, Jan 2026)</span></div>
      <div class="stat-row"><span class="stat-key">OBR Forecast 2026</span><span class="stat-val">Net housing additions projected to fall to 215,000 — well short of 300K target; 70% of SME builders cite market conditions as limiting</span></div>
      <div class="stat-row"><span class="stat-key">PropTech 2026</span><span class="stat-val">AI shifting "from experimentation to delivery"; digital identity wallets becoming standard for property transactions; next-gen NLIS launching in 2026</span></div>
      <div class="stat-row"><span class="stat-key">Competitors</span><span class="stat-val">Yourkeys/Zoopla (now dominant — acquired newhomesforsale.co.uk Feb 2026), ContactBuilder (Professional Edition), Housebuilder Pro — none have embedded fintech</span></div>
      <div class="stat-row"><span class="stat-key">SaleFish Fit</span><span class="stat-val badge badge-green">HIGH — fintech differentiator vs incumbents</span></div>
      <div style="height:10px;"></div>
      <div class="comp-section-title">Why Now</div>
      <div class="news-item">Massive political mandate for housing delivery — market will grow significantly over this Parliament</div>
      <div class="news-item">Zoopla's newhomesforsale.co.uk acquisition consolidates the portal layer but Yourkeys still needs a SaleFish-equivalent for payments & deposits</div>
      <div class="news-item">Incumbent platforms (ContactBuilder, Housebuilder Pro) still lack embedded fintech — SaleFish differentiator intact</div>
    </div>

    <!-- AUSTRALIA -->
    <div class="card region-card">
      <div class="region-city">🇦🇺 Australia</div>
      <div class="region-province">Pre-Sale Guarantees & Growing PropTech Market</div>
      <div class="region-price" style="font-size:22px;">OPPORTUNITY: <span style="color:var(--amber);">MEDIUM-HIGH</span></div>
      <div style="height:14px;"></div>
      <div class="stat-row"><span class="stat-key">Median Dwelling Value</span><span class="stat-val" style="color:var(--green);">AUD $922,838 (Feb 2026) — up 9.9% YoY (CoreLogic)</span></div>
      <div class="stat-row"><span class="stat-key">KPMG 2026 Forecast</span><span class="stat-val">National house prices +7.7%, units +7.1%; Perth leading (+12.8%), Brisbane (+10.9%)</span></div>
      <div class="stat-row"><span class="stat-key">Government Support</span><span class="stat-val">Help to Buy: federal govt contributing up to 40% for new builds; 5% deposit guarantee expected to unlock tens of thousands of new buyers</span></div>
      <div class="stat-row"><span class="stat-key">PropTech Market</span><span class="stat-val">AUD $1.83B (2025), growing to AUD $6.90B by 2035 (14.2% CAGR)</span></div>
      <div class="stat-row"><span class="stat-key">Competitors</span><span class="stat-val">Runway Proptech (land/community focused, not condo presale); no dominant off-the-plan SaaS platform</span></div>
      <div class="stat-row"><span class="stat-key">SaleFish Fit</span><span class="stat-val badge badge-amber">MEDIUM-HIGH — condo presale niche underserved</span></div>
      <div style="height:10px;"></div>
      <div class="comp-section-title">Why Now</div>
      <div class="news-item">Strong price forecast (+7.7% houses, +7.1% units KPMG 2026) with government demand stimulus — conditions favour new apartment launches</div>
      <div class="news-item">⚠️ Supply constraint: 380,000+ potential new homes stalled due to infrastructure delays, regulatory bottlenecks, and construction cost inflation (BDO Mar 2026) — demand outpaces delivery, supporting prices but limiting new project volume</div>
      <div class="news-item">Help to Buy scheme (40% federal contribution for new builds) bringing new buyers into market — increasing presale demand</div>
      <div class="news-item">Runway Proptech focuses on land/community development — high-rise off-the-plan condo presale gap exists for SaleFish</div>
    </div>

  </div>
</div>

<!-- ========== TAB 7: SWOT & STRATEGY ========== -->
<div id="tab-swot" class="tab-panel">
  <div class="section-header">
    <div class="section-title">SaleFish SWOT Analysis & Strategic Recommendations</div>
    <div class="section-sub">Internal and competitive positioning assessment with prioritized action items</div>
  </div>

  <div class="swot-grid" style="margin-bottom:32px;">
    <div class="swot-card swot-strengths">
      <div class="swot-label">💪 Strengths</div>
      <div class="swot-item"><span>→</span><span>Deep new home presale specialization — not a generalist platform</span></div>
      <div class="swot-item"><span>→</span><span>PropPay LX: unique PAD deposit payment capability, defensible moat</span></div>
      <div class="swot-item"><span>→</span><span>End-to-end workflow: from inventory setup to APS generation</span></div>
      <div class="swot-item"><span>→</span><span>Strong GTA builder/developer relationships and market knowledge</span></div>
      <div class="swot-item"><span>→</span><span>Canadian regulatory compliance expertise (Tarion, consumer protection)</span></div>
    </div>
    <div class="swot-card swot-weaknesses">
      <div class="swot-label">⚠️ Weaknesses</div>
      <div class="swot-item"><span>→</span><span>Limited VR/3D visualization vs Aareas and next-gen sales center tools</span></div>
      <div class="swot-item"><span>→</span><span>Smaller engineering team vs Lone Wolf, Constellation</span></div>
      <div class="swot-item"><span>→</span><span>Limited brand awareness outside GTA and Ontario market</span></div>
      <div class="swot-item"><span>→</span><span>API ecosystem less mature than enterprise competitors</span></div>
      <div class="swot-item"><span>→</span><span>Marketing/content investment lagging vs well-funded competitors</span></div>
    </div>
    <div class="swot-card swot-opportunities">
      <div class="swot-label">🚀 Opportunities</div>
      <div class="swot-item"><span>→</span><span>Embedded fintech tailwind directly benefits PropPay LX growth</span></div>
      <div class="swot-item"><span>→</span><span>Calgary and Alberta market expansion — underserved by competitors</span></div>
      <div class="swot-item"><span>→</span><span>AI-enhanced pricing engine and demand forecasting product features</span></div>
      <div class="swot-item"><span>→</span><span>US new home presale market entry — fragmented, no dominant player</span></div>
      <div class="swot-item"><span>→</span><span>Sustainability compliance tools for OHBA/CHBA net zero requirements</span></div>
    </div>
    <div class="swot-card swot-threats">
      <div class="swot-label">🎯 Threats</div>
      <div class="swot-item"><span>→</span><span>Blackline App hiring US sales leadership — expanding internationally beyond GTA</span></div>
      <div class="swot-item"><span>→</span><span>Constellation acquired RE/MAX data assets (Feb 2026) — 23 brands, dominant data position</span></div>
      <div class="swot-item"><span>→</span><span>Avesdo + VoPay embedded fintech partnership directly challenges PropPay LX value proposition</span></div>
      <div class="swot-item"><span>→</span><span>Lone Wolf API Portal + new CEO (Matt Fischer, ex-Bullhorn) — accelerating AI embedding and ecosystem lock-in</span></div>
      <div class="swot-item"><span>→</span><span>Toronto prices forecast ▼4.5% by Q4 2026 — potential slowdown in SaleFish home market</span></div>
    </div>
  </div>

  <div class="section-header">
    <div class="section-title">Strategic Recommendations</div>
  </div>

  <div class="rec-card">
    <div class="rec-num">1</div>
    <div class="rec-body">
      <div class="rec-title">Double Down on PropPay LX as Core Differentiator <span class="badge badge-red" style="margin-left:8px;">CRITICAL</span></div>
      <div class="rec-desc">PropPay LX is the only PAD-based deposit solution in Canadian new home sales. No competitor has this capability. Invest in making PropPay LX a flagship product feature — case studies, dedicated landing page, BILD/OHBA conference presence. The embedded fintech tailwind is worth $2B+ in investment globally. Lead with payments first.</div>
    </div>
  </div>

  <div class="rec-card">
    <div class="rec-num">2</div>
    <div class="rec-body">
      <div class="rec-title">Accelerate AI Features — Pricing Engine & Lead Scoring <span class="badge badge-red" style="margin-left:8px;">CRITICAL</span></div>
      <div class="rec-desc">Agentic AI and predictive analytics are where the most investment is flowing. SaleFish's inventory and pricing data is a goldmine for ML models. Prioritize: (1) AI-powered dynamic pricing recommendations, (2) buyer propensity scoring, (3) phase release optimization. These are features Blackline and Avesdo don't have and Lone Wolf is years away from in the new home context.</div>
    </div>
  </div>

  <div class="rec-card">
    <div class="rec-num">3</div>
    <div class="rec-body">
      <div class="rec-title">Geographic Expansion — Calgary First, Then US <span class="badge badge-amber" style="margin-left:8px;">HIGH</span></div>
      <div class="rec-desc">Calgary is the #1 Canadian expansion opportunity: strong new home demand, affordability advantage, underserved by competitors, and no Blackline App foothold. Assign a dedicated sales resource to Alberta, partner with a local CHBA chapter, and close 3 Calgary builder accounts in 2026 to establish beachhead. US presale market to follow in 2027.</div>
    </div>
  </div>

  <div class="rec-card">
    <div class="rec-num">4</div>
    <div class="rec-body">
      <div class="rec-title">Build Content & Thought Leadership Engine <span class="badge badge-amber" style="margin-left:8px;">HIGH</span></div>
      <div class="rec-desc">SaleFish has zero published content vs competitors who publish regular blog posts, market reports, and videos. This is a cheap, compounding competitive advantage. Launch a monthly "New Home Sales Insights" report, 2 blog posts/month on builder sales topics, and quarterly market data reports. This builds SEO moat, inbound leads, and positions SaleFish as the new home sales expert.</div>
    </div>
  </div>

  <div class="rec-card">
    <div class="rec-num">5</div>
    <div class="rec-body">
      <div class="rec-title">API/Integration Partner Program <span class="badge badge-blue" style="margin-left:8px;">MEDIUM</span></div>
      <div class="rec-desc">Lone Wolf and Constellation win enterprise deals partly through their deep integration ecosystems. SaleFish needs a formal API partner program targeting: Salesforce CRM, DocuSign/HelloSign, mortgage origination platforms, and accounting tools. A published API with documentation and a partner badge program would accelerate enterprise sales.</div>
    </div>
  </div>

  <div class="rec-card">
    <div class="rec-num">6</div>
    <div class="rec-body">
      <div class="rec-title">VR/AR Partnership — Don't Build, Partner <span class="badge badge-blue" style="margin-left:8px;">MEDIUM</span></div>
      <div class="rec-desc">Virtual tours and 3D visualization are table-stakes but building this in-house is expensive and not SaleFish's core. Instead, form a strategic partnership with a VR/AR provider (or Aareas Interactive themselves) for a certified integration. Offer "SaleFish + [Partner] Virtual Sales Center" as a bundled solution to developers. Capture the deal, outsource the hardware/visualization.</div>
    </div>
  </div>
</div>

<!-- ========== TAB 8: CONTENT STRATEGY ========== -->
<div id="tab-content" class="tab-panel">
  <div class="section-header">
    <div class="section-title">Content Strategy & Competitive Messaging</div>
    <div class="section-sub">Blog post ideas, social media angles, and SaleFish's competitive edge positioning vs each competitor</div>
  </div>

  <!-- BLOG POSTS -->
  <div class="cs-category">
    <div class="cs-category-title">📝 Blog Post Ideas <span class="badge badge-blue">SEO & Thought Leadership</span></div>
    <div class="cs-category-sub">Educate builders and developers. Position SaleFish as the new home sales expert. Own the search results for your buyers' questions.</div>

    <div class="cs-item">
      <div class="cs-item-title">How Pre-Authorized Debit is Transforming New Home Deposit Collection</div>
      <div class="cs-item-angle">Cover the pain of cheque-based deposits, NSFs, manual reconciliation. Show how PAD solves all of it. Natural showcase for PropPay LX.</div>
      <div class="cs-item-hook">SEO target: "new home deposit management software Canada" · "pre-authorized debit real estate"</div>
    </div>

    <div class="cs-item">
      <div class="cs-item-title">Q1 2026 Canadian New Home Sales Report: Montreal Surges While Toronto Adjusts</div>
      <div class="cs-item-angle">Data-driven quarterly report using CREA data (494K+ transactions forecast, +5.1%). Highlight Montreal's 6.1% growth vs Toronto's -7% dip. Position SaleFish as the authoritative new home data source.</div>
      <div class="cs-item-hook">SEO target: "new home sales forecast 2026" · "Canadian presale market outlook Q1 2026"</div>
    </div>

    <div class="cs-item">
      <div class="cs-item-title">Worksheet vs Online Reservation: What's the Right Presale Flow for Your Development?</div>
      <div class="cs-item-angle">Address the key buying question builders have when evaluating platforms. Compare approaches, pros/cons, when to use each. Positions SaleFish as flexible and expert.</div>
      <div class="cs-item-hook">SEO target: "presale worksheet management software" · "online condo reservation system"</div>
    </div>

    <div class="cs-item">
      <div class="cs-item-title">Agentic AI in New Home Sales: From Hype to Production in 2026</div>
      <div class="cs-item-angle">2026 is the year AI goes from experimentation to adoption in PropTech. Cover how autonomous AI agents handle buyer intake, follow-up, and qualification. Position SaleFish as forward-thinking. Tease upcoming AI product features.</div>
      <div class="cs-item-hook">SEO target: "agentic AI real estate 2026" · "AI proptech new home sales"</div>
    </div>

    <div class="cs-item">
      <div class="cs-item-title">The Digital Sales Center Checklist: What Every GTA Builder Needs in 2026</div>
      <div class="cs-item-angle">Practical checklist covering online reservations, e-signatures, deposit payments, buyer portal, analytics. Naturally showcases SaleFish's capabilities without being a product pitch.</div>
      <div class="cs-item-hook">SEO target: "digital sales center software builders" · "condo presale technology checklist"</div>
    </div>

    <div class="cs-item">
      <div class="cs-item-title">5 Ways Builders Are Losing Deposits (And How to Stop It)</div>
      <div class="cs-item-angle">Pain-focused piece on deposit NSFs, cheque handling, manual tracking errors, and missed follow-ups. Each problem has a PropPay LX / SaleFish solution. High conversion value.</div>
      <div class="cs-item-hook">SEO target: "real estate deposit management" · "NSF deposit new homes"</div>
    </div>
  </div>

  <!-- SOCIAL MEDIA -->
  <div class="cs-category">
    <div class="cs-category-title">📱 Social Media Post Angles <span class="badge badge-purple">LinkedIn · Twitter/X · Instagram</span></div>
    <div class="cs-category-sub">Short, punchy, and shareable. Builder and developer decision-makers are on LinkedIn. Hit them where they scroll.</div>

    <div class="cs-item">
      <div class="cs-item-title">"The Stat Drop" — Market Data Posts</div>
      <div class="cs-item-angle">Post weekly market data with a brief insight. Example: "Montreal new home prices: +6.1% YoY. Calgary: +2.2%. Toronto: adjusting at -7%. Where are you launching next? #NewHomeSales #PropTech #CanadianRE"</div>
      <div class="cs-item-hook">Best for: LinkedIn · Builds authority · Easy to make · Post weekly</div>
    </div>

    <div class="cs-item">
      <div class="cs-item-title">"Builder POV" — Behind-the-Scenes Stories</div>
      <div class="cs-item-angle">Short video or carousel showing the SaleFish sales day: how a worksheet goes from reserved to contracted to deposit collected. Real workflow, real product. Show don't tell.</div>
      <div class="cs-item-hook">Best for: LinkedIn + Instagram · Video/carousel format · 2x/month</div>
    </div>

    <div class="cs-item">
      <div class="cs-item-title">"PropTech Trend Watch" — Industry News Commentary</div>
      <div class="cs-item-angle">React to PropTech news with a hot take in 3–4 sentences. Example: "Lone Wolf just raised $50M. Here's what it means for Canadian builders and why vertical specialists still win." Fast to produce, positions Andrew as a voice in the space.</div>
      <div class="cs-item-hook">Best for: LinkedIn · News-jacking · Andrew's personal brand + SaleFish page</div>
    </div>

    <div class="cs-item">
      <div class="cs-item-title">"Client Win" — Anonymous Case Studies</div>
      <div class="cs-item-angle">Short format: "A GTA highrise builder reduced deposit NSFs by 80% after switching to PAD collection. Here's the 3-step workflow they use." Real results, anonymized. Drives inbound inquiries.</div>
      <div class="cs-item-hook">Best for: LinkedIn · 1x/month · Strong for sales follow-up use</div>
    </div>

    <div class="cs-item">
      <div class="cs-item-title">"Feature Friday" — Product Education Posts</div>
      <div class="cs-item-angle">Weekly product tip or feature highlight in < 150 words. "Did you know SaleFish can automatically send a PAD authorization to a buyer the moment they reserve a unit? Here's how." Educates existing clients, attracts prospects.</div>
      <div class="cs-item-hook">Best for: LinkedIn + Twitter/X · Drives product adoption + SEO · Low effort</div>
    </div>
  </div>

  <!-- COMPETITIVE EDGE POSITIONING -->
  <div class="cs-category">
    <div class="cs-category-title">⚔️ SaleFish Competitive Edge vs Each Competitor</div>
    <div class="cs-category-sub">How to position SaleFish when prospects bring up or are currently using a competitor. Be honest, be sharp, close the deal.</div>

    <table class="edge-table" style="margin-top:12px;">
      <thead>
        <tr>
          <th style="width:160px;">Competitor</th>
          <th>Their Pitch</th>
          <th>SaleFish Counter-Position</th>
          <th>Killer Question to Ask</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><span class="edge-comp">Blackline App</span><br><span class="badge badge-red" style="margin-top:4px;">HIGH</span></td>
          <td>"We're Toronto-based and focused on worksheet management for new homes."</td>
          <td>SaleFish is the only platform that also handles deposit collection via PAD — Blackline can't touch that. We go from worksheet all the way to funds in the builder's account without a single cheque.</td>
          <td>"When a buyer bounces a deposit cheque, what's your current recovery workflow? How long does that take?"</td>
        </tr>
        <tr>
          <td><span class="edge-comp">Constellation RE Group</span><br><span class="badge badge-red" style="margin-top:4px;">HIGH</span></td>
          <td>"We have deep enterprise resources and an integrated suite of real estate tools."</td>
          <td>Constellation buys and integrates. SaleFish builds for new home presales. When you call Constellation, you get a generalist RE platform that was stitched together. When you call SaleFish, you get a team that breathes presale workflows.</td>
          <td>"How many of their tools were actually built for new home presales vs retrofitted from resale or brokerage platforms?"</td>
        </tr>
        <tr>
          <td><span class="edge-comp">Lone Wolf Technologies</span><br><span class="badge badge-amber" style="margin-top:4px;">MOD-HIGH</span></td>
          <td>"We power 1.5M+ agents and brokerages across Canada. Scale and reliability."</td>
          <td>Lone Wolf owns the brokerage space — SaleFish owns the new home builder space. Lone Wolf is a brokerage tool being pushed into presales. SaleFish was built from day one for presale inventory, phased releases, worksheets, and PAD deposits. No comparison on fit.</td>
          <td>"Has Lone Wolf's team ever been through a phased new home launch? Do they understand holding periods and Tarion obligations?"</td>
        </tr>
        <tr>
          <td><span class="edge-comp">Avesdo</span><br><span class="badge badge-blue" style="margin-top:4px;">MEDIUM</span></td>
          <td>"We're built for new home presales with embedded payments via VoPay."</td>
          <td>Avesdo just partnered with VoPay for payments — they're chasing what PropPay LX has had for years. Their fintech is bolted on; ours is built in. Plus, Avesdo is BC-native — Ontario presales have different Tarion obligations and buyer expectations. SaleFish is built in Ontario, for Ontario.</td>
          <td>"Has your team dealt with Tarion registration workflows for Ontario specifically? What about the differences in HST rebate documentation?"</td>
        </tr>
        <tr>
          <td><span class="edge-comp">Spark Real Estate</span><br><span class="badge badge-amber" style="margin-top:4px;">MED-HIGH</span></td>
          <td>"Full lifecycle platform — marketing, sales, and now post-sale with Juniper."</td>
          <td>Spark just acquired Juniper for warranty/homeowner care — they're spreading wide. SaleFish goes deep. Worksheet, reservation, deposit via PAD, APS — the actual transaction. Spark is broad but thin where it counts: no PAD deposits, no pricing engine, no Ontario compliance depth.</td>
          <td>"What happens after a buyer clicks 'I'm Interested' in Spark? How does the actual reservation, deposit, and contract process work?"</td>
        </tr>
        <tr>
          <td><span class="edge-comp">Aareas Interactive</span><br><span class="badge badge-blue" style="margin-top:4px;">MEDIUM</span></td>
          <td>"We create immersive interactive sales center experiences with touchscreen technology."</td>
          <td>Aareas is the best in the room when it comes to touchscreens and virtual walkthroughs — that's their whole world. SaleFish is what runs the actual sale. SaleFish + Aareas is a dream stack: Aareas shows the suite, SaleFish closes it. They're complementary, not competing.</td>
          <td>"After a buyer falls in love with a suite at the sales center, what happens next? Who handles the reservation, deposit, and APS?"</td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- CONTENT CALENDAR SKELETON -->
  <div class="cs-category">
    <div class="cs-category-title">📅 Suggested Monthly Content Calendar</div>
    <div class="cs-category-sub">A simple repeating framework to maintain consistent content output without burning out.</div>
    <div class="grid-2">
      <div>
        <div class="comp-section-title">Week 1</div>
        <div class="news-item">Blog post — educational (600–900 words)</div>
        <div class="news-item">LinkedIn: Market stat drop</div>
        <div class="news-item">LinkedIn: Feature Friday post</div>
        <div style="height:14px;"></div>
        <div class="comp-section-title">Week 3</div>
        <div class="news-item">Blog post — product/case study focused</div>
        <div class="news-item">LinkedIn: Builder POV story / short video</div>
        <div class="news-item">LinkedIn: PropTech trend commentary</div>
      </div>
      <div>
        <div class="comp-section-title">Week 2</div>
        <div class="news-item">LinkedIn: Client win (anonymous case study)</div>
        <div class="news-item">LinkedIn: Feature Friday post</div>
        <div class="news-item">Twitter/X: PropTech news hot take</div>
        <div style="height:14px;"></div>
        <div class="comp-section-title">Week 4</div>
        <div class="news-item">LinkedIn: Market stat drop</div>
        <div class="news-item">LinkedIn: Feature Friday post</div>
        <div class="news-item">Review: Update dashboard + SEO keywords</div>
      </div>
    </div>
  </div>

</div>

</div><!-- end .content -->

<!-- FOOTER -->
<div class="footer">
  <div>SaleFish Competitor Intelligence Dashboard · <span class="current-date"></span></div>
  <div>Confidential — Internal Use Only · Director of Product · Andrew Blair</div>
  <div id="footer-source">Sources: CREA, TRREB (Mar 2026), Greater Vancouver Realtors (Mar 2026), CREB Calgary (Mar 2026), Bank of Canada (Apr 1 Summary of Deliberations), TD Economics, WOWA.ca, Polymarket, CRETI PropTech Funding, Precedence Research, Fortune Business Insights, Market.us, GlobeNewsWire, RISMedia, Inman, LinkedIn, NAR Tech Survey, NAHB/IBS 2026, PropertyGuru, Zoopla/newhomesforsale.co.uk (Feb 2026), MHub Malaysia, Proptech Australia, BDO Australia, KPMG AU, Dubai Land Dept, PropTech Connect Dubai 2026, AGBI, Gulf News, Business Today ME, Invezz, The Middle East Insider (Q1 2026), UK MHCLG, ONS, OBR, Fifth Wall, Zawya, RBC Economics (Apr 2026), ERA Singapore, StackedHomes, Nawy · Updated Apr 9, 2026</div>
</div>

<script>
  // Date stamps
  const now = new Date();
  const opts = { year: 'numeric', month: 'long', day: 'numeric' };
  const dateStr = now.toLocaleDateString('en-CA', opts);
  document.getElementById('header-date').textContent = 'Last Updated: ' + dateStr;
  document.querySelectorAll('.current-date').forEach(el => el.textContent = dateStr);

  // Tab navigation
  function showTab(name) {
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    const panel = document.getElementById('tab-' + name);
    if (panel) panel.classList.add('active');
    event.currentTarget.classList.add('active');
  }
</script>

<script>
  // Password gate
  const GATE_HASH = '7e5d18eb531e0ca9088319b8151ddf7d31a166f0a2f15b1eb5642685c8d18b3e';

  async function sha256(str) {
    const buf = await crypto.subtle.digest('SHA-256', new TextEncoder().encode(str));
    return Array.from(new Uint8Array(buf)).map(b => b.toString(16).padStart(2, '0')).join('');
  }

  async function checkGate() {
    const val = document.getElementById('gate-pwd').value;
    const hash = await sha256(val);
    if (hash === GATE_HASH) {
      sessionStorage.setItem('intel_auth', '1');
      document.getElementById('gate-overlay').remove();
    } else {
      document.getElementById('gate-error').textContent = 'Incorrect password. Please try again.';
      document.getElementById('gate-pwd').value = '';
      document.getElementById('gate-pwd').focus();
    }
  }

  // Allow Enter key to submit
  document.getElementById('gate-pwd').addEventListener('keydown', e => {
    if (e.key === 'Enter') checkGate();
  });

  // Skip gate if already authenticated this session
  if (sessionStorage.getItem('intel_auth') === '1') {
    document.getElementById('gate-overlay').remove();
  }
</script>
</body>
</html>
