import json
import os
import pycountry
import gettext
import shutil


CDIR = os.path.dirname(os.path.realpath(__file__))

LOCALES = os.path.join(CDIR, "locales-wp.json")

OUT_DATA_DIR = os.path.join(CDIR, "../js/data/")
OUT_TRANS_DIR = os.path.join(CDIR, "../js/data/translations/")
OUT_TRANS_DIR_PHP = os.path.join(CDIR, "../data/translations/")
OUT_PHP = os.path.join(CDIR, "../data/countries.php")

OUT_STATES = "states.js"
OUT_TRANS = "strings.js"
OUT_TRANS_PHP = "countries.php"

STATES_TEMPLATE = u"""// WARNING: This file is auto generated. Please, do not edit.

var wpautotermsStates = wpautotermsStates || {{}};
wpautotermsStates.states = {};
"""

TRANS_TEMPLATE = u"""// WARNING: This file is auto generated. Please, do not edit.

var wpautotermsStates = wpautotermsStates || {{}};
wpautotermsStates.translations = {};
"""

PHP_TEMPLATE = u"""<?php
// WARNING: This file is auto generated. Please, do not edit.

function wpautoterms_country_locales () {{
	return json_decode('{}', true);
}}

function wpautoterms_countries () {{
	return json_decode('{}', true);
}}
"""

PHP_TRANS_TEMPLATE = u"""<?php
// WARNING: This file is auto generated. Please, do not edit.

function wpautoterms_country_translations_{locale} () {{
	return json_decode('{data}', true);
}}
"""

def filter_states(subdiv):
    if subdiv.country_code == "GB":
        return False
    if subdiv.country_code == "PH" and subdiv.code == "PH-00":
        return True
    t = subdiv.type.lower()
    return t in ("state", "province", "territory", "autonomous republic")

def run():
	with open(LOCALES, "r") as f:
		trans_locales = map(lambda x: x['language'], json.loads("".join(f.readlines()))['translations'])
	countries = {}
	states = []
	for c in pycountry.countries:
		code = c.alpha_2
		s = pycountry.subdivisions.get(country_code=code)
		s = filter(filter_states, s)
		countries[code]=map(lambda x:x.code, s)
		states+=s
	print "Saving {} subdivisions and {} countries...".format(len(states), len(countries.keys()))
	path = os.path.join(OUT_DATA_DIR, OUT_STATES)
	with open(path, "w") as f:
		f.write(STATES_TEMPLATE.format(json.dumps(countries, sort_keys=True, indent=2)))
	shutil.rmtree(OUT_TRANS_DIR, ignore_errors=True)
	shutil.rmtree(OUT_TRANS_DIR_PHP, ignore_errors=True)
	locales = {}
	for x in trans_locales:
		_,l=load_translation(x, ["iso3166","iso3166-1","iso3166_1"])
		locales.setdefault(l.split('_')[0],[]).append(l)
	for l_key,l_arr in locales.items():
		if not any(map(lambda x: x==l_key, l_arr)):
			l_arr[-1]=l_key
		for x in l_arr:
			translate(l_key if len(l_arr)<2 else x, x, states)
	print "Saved for {} locales.".format(len(locales))
	with open(OUT_PHP, "w") as f:
		out = json.dumps(list(locales), sort_keys=True, indent=2)
		out_countries = json.dumps(list(countries), sort_keys=True, indent=2)
		f.write(PHP_TEMPLATE.format(out.replace("'","\\'"), out_countries.replace("'","\\'")).encode("utf8"))


def load_translation(locale, iso):
	lang = locale.split('_')[0]
	l = set([locale, lang])
	for x in l:
		for y in iso:
			try:
				t=gettext.translation(y, pycountry.LOCALES_DIR,languages=[x])
				return t, x
			except Exception:
				pass
	return None, locale

def translate(locale, load_locale, states):
	tc,_ = load_translation(load_locale, ["iso3166","iso3166-1","iso3166_1"])
	if tc is None:
		tc = gettext.NullTranslations()
	ts,_ = load_translation(load_locale, ["iso3166-2","iso3166_2"])
	if ts is None:
		ts = tc
	s = {}
	ct = {}
	for c in pycountry.countries:
		ct[c.alpha_2]=s[c.alpha_2]=tc.ugettext(c.name)
	for state in states:
		s[state.code]=ts.ugettext(state.name)
	trans_dir = os.path.join(OUT_TRANS_DIR, locale)
	if not os.path.exists(trans_dir):
		os.makedirs(trans_dir)
	out = json.dumps(s, ensure_ascii=False, sort_keys=True, indent=2)
	with open(os.path.join(trans_dir, OUT_TRANS), "w") as f:
		f.write(TRANS_TEMPLATE.format(out).encode("utf8"))

	trans_dir = os.path.join(OUT_TRANS_DIR_PHP, locale)
	if not os.path.exists(trans_dir):
		os.makedirs(trans_dir)
	with open(os.path.join(trans_dir, OUT_TRANS_PHP), "w") as f:
		f.write(PHP_TRANS_TEMPLATE.format(locale=locale.lower(), data=out.replace("'","\\'")).encode("utf8"))




if __name__ == "__main__":
	run()
	print("Done.")
