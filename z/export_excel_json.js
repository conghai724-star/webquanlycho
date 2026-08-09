const XLSX = require('xlsx');
const fs = require('fs');
const path = require('path');

const filePath = path.join(__dirname, 'ĐĂNG KÝ CHỢ QUYẾT THẮNG 2026 (1).xlsx');
const wb = XLSX.readFile(filePath);

const ws = wb.Sheets[wb.SheetNames[0]];
const allRows = XLSX.utils.sheet_to_json(ws, { header: 1, defval: '' });

const wsNghi = wb.Sheets['nghỉ'];
const nghiRows = wsNghi ? XLSX.utils.sheet_to_json(wsNghi, { header: 1, defval: '' }) : [];

function isSectionHeader(row) {
  if (typeof row[0] === 'string' && row[0].length > 15) return true;
  if ((row[0] === '' || row[0] === null || row[0] === undefined) && typeof row[1] === 'string' && row[1].length > 15) return true;
  return false;
}

function isTotalRow(row) {
  var c1 = String(row[1] || '').trim();
  return c1 === 'Tổng' || c1 === 'Tổng cộng';
}

function isDataRow(row) {
  var name = String(row[1] || '').trim().replace(/\r?\n/g, ' ');
  if (!name || name.length < 2) return false;
  if (name === 'Tổng' || name === 'Tổng cộng' || name === 'Lập biểu') return false;

  var stt = row[0];
  if (typeof stt === 'number' || stt === '' || stt === null || stt === undefined) {
    var hd = row[3];
    var numLots = row[5];
    if (hd !== '' || (typeof numLots === 'number' && numLots > 0)) return true;
  }
  return false;
}

let result = [];
let currentAreaId = null;
let traderIdCounter = 1;
let seenContractNumbers = {};

function detectAreaId(headerText) {
  if (!headerText || typeof headerText !== 'string') return null;
  var s = headerText.toLowerCase().trim();
  if (s.includes('bùi thị xuân') && s.includes('105')) return 1;
  if (s.includes('bùi thị xuân') && s.includes('85')) return 2;
  if (s.includes('ngô quyền')) return 3;
  if (s.includes('3*1') || (s.includes('ki ốt') && s.includes('nhà lồng'))) return 4;
  if (s.includes('nhà lồng 2.2')) return 5;
  if (s.includes('nhà lồng 2') && s.includes('ăn') && !s.includes('2.2')) return 6;
  if (s.includes('nhà lồng 3') && (s.includes('cá') || s.includes('cá'))) return 7;
  if (s.includes('50.000') && s.includes('hàng rau')) return 8;
  if (s.includes('50.000') && (s.includes('nhà lồng 1') || s.includes('1.9'))) return 9;
  if (s.includes('50.000') && s.includes('1.5') && !s.includes('1.9')) return 10;
  if (s.includes('không có mái che') && (s.includes('hàng ăn') || s.includes('hàng rau')) && !s.includes('50.000')) return 11;
  if (s.includes('nhà lồng 1') && !s.includes('50.000')) return 12;
  if (s.includes('hoàng hoa thám')) return 13;
  if (s.includes('bốc thăm') || s.includes('chợ đêm')) return 14;
  return null;
}

function padNum(n, len) {
  return String(n).padStart(len || 3, '0');
}

for (let i = 8; i < allRows.length; i++) {
  let row = allRows[i];

  if (isSectionHeader(row)) {
    var txt = typeof row[0] === 'string' && row[0].length > 15 ? row[0] : row[1];
    currentAreaId = detectAreaId(txt);
    continue;
  }

  if (isTotalRow(row)) continue;
  if (!isDataRow(row)) continue;
  if (currentAreaId === null) continue;

  var fullname = String(row[1]).trim();
  var address = String(row[2] || '').trim();
  var hdNum = row[3];
  var totalArea = (typeof row[8] === 'number') ? row[8] : 0;
  var unitPrice = (typeof row[9] === 'number') ? row[9] : 0;
  var monthlyRent = (typeof row[10] === 'number') ? row[10] : 0;
  var yearlyRent = (typeof row[11] === 'number') ? row[11] : 0;
  var monthlyWaste = (typeof row[12] === 'number') ? row[12] : 0;
  var yearlyWaste = (typeof row[13] === 'number') ? row[13] : 0;
  var totalAmount = (typeof row[14] === 'number') ? row[14] : 0;

  var excelContractBase = '';
  if (hdNum !== '' && hdNum !== null && hdNum !== undefined) {
    excelContractBase = String(hdNum).trim();
    if (/^\d{1,2}$/.test(excelContractBase)) {
      excelContractBase = padNum(parseInt(excelContractBase), 3);
    }
  } else {
    excelContractBase = 'AUTO-' + padNum(traderIdCounter, 3);
  }

  var baseContractNum = excelContractBase;
  var cSuffix = 2;
  while (result.some(t => t.excel_contract_base === excelContractBase)) {
    excelContractBase = baseContractNum + '_' + cSuffix;
    cSuffix++;
  }

  var contractNum = excelContractBase + '/2026/HĐTQLC';
  var isService = (currentAreaId === 11);

  result.push({
    trader_fullname: fullname,
    trader_address: address,
    excel_contract_base: baseContractNum,
    contract_number: contractNum,
    area_size: isService ? 0 : totalArea,
    base_price: isService ? monthlyRent : unitPrice,
    yearly_rent: yearlyRent,
    yearly_waste: yearlyWaste,
    total_amount: totalAmount,
    area_id: currentAreaId
  });
  
  traderIdCounter++;
  seenContractNumbers[baseContractNum] = true;
}

// Thêm hộ nghỉ
for (var n = 2; n < nghiRows.length; n++) {
  var nrow = nghiRows[n];
  var nName = String(nrow[1] || '').trim();
  if (nName.length < 2) continue;

  var nHd = nrow[3];
  var nContractBase = '';
  if (nHd !== '' && nHd !== null && nHd !== undefined) {
    nContractBase = String(nHd).trim();
    if (/^\d{1,2}$/.test(nContractBase)) nContractBase = padNum(parseInt(nContractBase), 3);
  } else {
    nContractBase = 'NGHI-' + padNum(n, 3);
  }

  var nBaseContractNum = nContractBase;
  var ncSuffix = 2;
  while (result.some(t => t.excel_contract_base === nContractBase)) {
    nContractBase = nBaseContractNum + '_' + ncSuffix;
    ncSuffix++;
  }

  var nContractNum = nContractBase + '/2026/HĐTQLC';
  if (seenContractNumbers[nContractBase]) {
    continue;
  }

  var nAddress = String(nrow[2] || '').trim();
  var nTotalArea = (typeof nrow[8] === 'number') ? nrow[8] : 0;
  var nUnitPrice = (typeof nrow[9] === 'number') ? nrow[9] : 0;
  var nYearlyRent = (typeof nrow[11] === 'number') ? nrow[11] : 0;
  var nYearlyWaste = (typeof nrow[13] === 'number') ? nrow[13] : 0;
  var nTotalAmount = (typeof nrow[14] === 'number') ? nrow[14] : 0;

  result.push({
    trader_fullname: nName,
    trader_address: nAddress,
    excel_contract_base: nBaseContractNum,
    contract_number: nContractNum,
    area_size: nTotalArea,
    base_price: nUnitPrice,
    yearly_rent: nYearlyRent,
    yearly_waste: nYearlyWaste,
    total_amount: nTotalAmount,
    area_id: 12
  });
}

fs.writeFileSync(path.join(__dirname, 'excel_data.json'), JSON.stringify(result, null, 2), 'utf8');
console.log('Exported ' + result.length + ' rows to json.');
