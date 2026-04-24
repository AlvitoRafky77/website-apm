import zipfile
import xml.etree.ElementTree as ET

xlsx_path = "SRS PT Agro Pangan Maju.xlsx"
z = zipfile.ZipFile(xlsx_path)

ns = {
    "a": "http://schemas.openxmlformats.org/spreadsheetml/2006/main",
    "r": "http://schemas.openxmlformats.org/officeDocument/2006/relationships",
}

wb = ET.fromstring(z.read("xl/workbook.xml"))
rels = ET.fromstring(z.read("xl/_rels/workbook.xml.rels"))
rid_to_target = {r.attrib["Id"]: r.attrib["Target"] for r in rels}

sheets = []
for s in wb.find("a:sheets", ns):
    name = s.attrib["name"]
    rid = s.attrib["{http://schemas.openxmlformats.org/officeDocument/2006/relationships}id"]
    sheets.append((name, "xl/" + rid_to_target[rid]))

sst = []
if "xl/sharedStrings.xml" in z.namelist():
    root = ET.fromstring(z.read("xl/sharedStrings.xml"))
    sst = [t.text or "" for t in root.iter("{http://schemas.openxmlformats.org/spreadsheetml/2006/main}t")]

def cell_value(c):
    t = c.attrib.get("t")
    v = c.find("{http://schemas.openxmlformats.org/spreadsheetml/2006/main}v")
    if v is None:
        return ""
    if t == "s":
        return sst[int(v.text)]
    return v.text

name, path = [p for p in sheets if p[0] == "Requirement Revision"][0]
ws = ET.fromstring(z.read(path))

for row in ws.iter("{http://schemas.openxmlformats.org/spreadsheetml/2006/main}row"):
    vals = [cell_value(c) for c in row.iter("{http://schemas.openxmlformats.org/spreadsheetml/2006/main}c")]
    print("\t".join(vals))
