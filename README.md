# OpenAIRE Broker API – Enrichments

The OpenAIRE Broker API – Enrichments plugin allows editors in OJS to view enrichment suggestions provided by the [OpenAIRE Broker API](https://graph.openaire.eu/docs/apis/broker-api/).

Enrichments are not automatically imported or written into article metadata. Instead, the plugin visualizes enrichment results for registered subscriptions via the [OpenAIRE Provide service](https://provide.openaire.eu/home).

- You can explore and test the API here: **http://api.openaire.eu/broker/swagger-ui/index.html**

# Features:
- Adds a tab in **Website Settings** with a list of all enrichments available for the journal.
- Displays **enrichments per article** in the **Publication** inside Workflow.
- Connects to **OpenAIRE Broker API** based on your journal's **subscriptions** in **OpenAIRE Provide**.
- The plugin is **read-only**: enrichments are displayed but not stored or edited.
- **Note:** API responses may load slowly, especially for journals with large datasets.

# Screenshots
![Journal-level enrichments](https://munispace.muni.cz/public/craft-oa/enrichments-tab.png)
![Article-level enrichments](https://munispace.muni.cz/public/craft-oa/article-enrichments.png)

# License
This plugin is licensed under the GNU General Public License v3. See the file LICENSE for the complete terms of this license.

# System Requirements
OJS 3.2.1 or later.

# Version History
- Version 3.4.0.0 – Support for OJS 3.4.0
- Version 3.3.0.0 – Support for OJS 3.3.0
- Version 3.2.0.0 – Support for OJS 3.2.0

# Installation
Installing using a release from GitHub:
1.	Download the latest compatible release and unzip it.
2.	Move the **openAIREBrokerService** folder to the OJS **plugins/generic/** folder.
3.  Run the update command from the command line in the OJS root folder: 
**php tools/update.php update**
4.	Go to **Settings → Website → Plugins → Generic Plugin → OpenAIRE Broker Service** and enable the plugin.

# Third-Party Software:
This plugin communicates with:

- [OpenAIRE Broker API] (https://graph.openaire.eu/docs/apis/broker-api/)
- [OpenAIRE Provide Service] (https://provide.openaire.eu/)

To receive enrichments, you must first register your journal in **OpenAIRE Provide** and subscribe to enrichment services.

# Credit
---------------
This plugin was developed at the [Masaryk University Press - Munipress](https://www.press.muni.cz), as part of its active participation in the [Craft-OA project](https://www.craft-oa.eu/).

The development was initiated, coordinated, and technically supported by Munipress.

