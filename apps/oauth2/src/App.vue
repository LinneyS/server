<!--
  - @copyright Copyright (c) 2018 Roeland Jago Douma <roeland@famdouma.nl>
  -
  - @author Roeland Jago Douma <roeland@famdouma.nl>
  -
  - @license GNU AGPL version 3 or any later version
  -
  - This program is free software: you can redistribute it and/or modify
  - it under the terms of the GNU Affero General Public License as
  - published by the Free Software Foundation, either version 3 of the
  - License, or (at your option) any later version.
  -
  - This program is distributed in the hope that it will be useful,
  - but WITHOUT ANY WARRANTY; without even the implied warranty of
  - MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  - GNU Affero General Public License for more details.
  -
  - You should have received a copy of the GNU Affero General Public License
  - along with this program. If not, see <http://www.gnu.org/licenses/>.
  -
  -->
<template>
	<SettingsSection :title="t('oauth2', 'OAuth 2.0 clients')"
		:description="t('oauth2', 'OAuth 2.0 allows external services to request access to {instanceName}.', { instanceName })"
		:doc-url="oauthDocLink">
		<table v-if="clients.length > 0" class="grid">
			<thead>
				<tr>
					<th id="headerContent" />
					<th id="headerRemove">
&nbsp;
					</th>
				</tr>
			</thead>
			<tbody>
				<OAuthItem v-for="client in clients"
					:key="client.id"
					:client="client"
					@delete="deleteClient" />
			</tbody>
		</table>

		<br>
		<h3>{{ t('oauth2', 'Add client') }}</h3>
		<span v-if="newClient.error" class="msg error">{{ newClient.errorMsg }}</span>
		<form @submit.prevent="addClient">
			<input id="name"
				v-model="newClient.name"
				type="text"
				name="name"
				:placeholder="t('oauth2', 'Name')">
			<input id="redirectUri"
				v-model="newClient.redirectUri"
				type="url"
				name="redirectUri"
				:placeholder="t('oauth2', 'Redirection URI')">
			<Button class="inline-button">
				{{ t('oauth2', 'Add') }}
			</Button>
		</form>
	</SettingsSection>
</template>

<script>
import axios from '@nextcloud/axios'
import OAuthItem from './components/OAuthItem'
import { generateUrl } from '@nextcloud/router'
import { getCapabilities } from '@nextcloud/capabilities'
import SettingsSection from '@nextcloud/vue/dist/Components/SettingsSection'
import Button from '@nextcloud/vue/dist/Components/Button'
import { loadState } from '@nextcloud/initial-state'

export default {
	name: 'App',
	components: {
		OAuthItem,
		SettingsSection,
		Button,
	},
	props: {
		clients: {
			type: Array,
			required: true,
		},
	},
	data() {
		return {
			newClient: {
				name: '',
				redirectUri: '',
				errorMsg: '',
				error: false,
			},
			oauthDocLink: loadState('oauth2', 'oauth2-doc-link'),
		}
	},
	computed: {
		instanceName() {
			return getCapabilities().theming.name
		},
	},
	methods: {
		deleteClient(id) {
			axios.delete(generateUrl('apps/oauth2/clients/{id}', { id }))
				.then((response) => {
					// eslint-disable-next-line vue/no-mutating-props
					this.clients = this.clients.filter(client => client.id !== id)
				})
		},
		addClient() {
			this.newClient.error = false

			axios.post(
				generateUrl('apps/oauth2/clients'),
				{
					name: this.newClient.name,
					redirectUri: this.newClient.redirectUri,
				}
			).then(response => {
				// eslint-disable-next-line vue/no-mutating-props
				this.clients.push(response.data)

				this.newClient.name = ''
				this.newClient.redirectUri = ''
			}).catch(reason => {
				this.newClient.error = true
				this.newClient.errorMsg = reason.response.data.message
			})
		},
	},
}
</script>
<style scoped>
	table {
		max-width: 800px;
	}

	/** Overwrite button height and position to be aligned with the text input */
	.inline-button {
		min-height: 34px !important;
		display: inline-flex !important;
	}
</style>
