<?php
declare(strict_types=1);
/**
 * @copyright Copyright (c) 2022 Joas Schilling <coding@schilljs.com>
 *
 * @author Joas Schilling <coding@schilljs.com>
 * @author John Molakvoæ <skjnldsv@protonmail.com>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */
namespace OCA\Theming\Themes;

use OCA\Theming\ITheme;

class DarkTheme extends DefaultTheme implements ITheme {

	public function getId(): string {
		return 'dark';
	}

	public function getMediaQuery(): string {
		return '(prefers-color-scheme: dark)';
	}

	public function getTitle(): string {
		return $this->l->t('Dark theme');
	}

	public function getEnableLabel(): string {
		return $this->l->t('Enable dark theme');
	}

	public function getDescription(): string {
		return $this->l->t('A dark theme to ease your eyes by reducing the overall luminosity and brightness.');
	}

	public function getCSSVariables(): array {
		$defaultVariables = parent::getCSSVariables();

		$colorMainText = '#D8D8D8';
		$colorMainBackground = '#171717';
		$colorMainBackgroundRGB = join(',', $this->util->hexToRGB($colorMainBackground));
		$colorBoxShadow = $this->util->darken($colorMainBackground, 70);
		$colorBoxShadowRGB = join(',', $this->util->hexToRGB($colorBoxShadow));
		$colorPrimaryLight = $this->util->mix($this->primaryColor, $colorMainBackground, -80);

		return array_merge($defaultVariables, [
			'--color-main-text' => $colorMainText,
			'--color-main-background' => $colorMainBackground,
			'--color-main-background-rgb' => $colorMainBackgroundRGB,

			'--color-background-hover' => $this->util->lighten($colorMainBackground, 4),
			'--color-background-dark' => $this->util->lighten($colorMainBackground, 7),
			'--color-background-darker' => $this->util->lighten($colorMainBackground, 14),

			'--color-placeholder-light' => $this->util->lighten($colorMainBackground, 10),
			'--color-placeholder-dark' => $this->util->lighten($colorMainBackground, 20),

			'--color-primary-hover' => $this->util->mix($this->primaryColor, $colorMainBackground, 60),
			'--color-primary-light' => $colorPrimaryLight,
			'--color-primary-light-hover' => $this->util->mix($colorPrimaryLight, $colorMainText, 90),
			'--color-primary-element' => $this->util->elementColor($this->primaryColor, false),
			'--color-primary-element-hover' => $this->util->mix($this->util->elementColor($this->primaryColor, false), $colorMainBackground, 80),
			'--color-primary-element-light' => $this->util->lighten($this->util->elementColor($this->primaryColor, false), 15),
			'--color-primary-element-lighter' => $this->util->mix($this->util->elementColor($this->primaryColor, false), $colorMainBackground, -70),

			'--color-text-maxcontrast' => $this->util->darken($colorMainText, 30),
			'--color-text-light' => $this->util->darken($colorMainText, 10),
			'--color-text-lighter' => $this->util->darken($colorMainText, 20),

			'--color-loading-light' => '#777',
			'--color-loading-dark' => '#CCC',

			'--color-box-shadow-rgb' => $colorBoxShadowRGB,

			'--color-border' => $this->util->lighten($colorMainBackground, 7),
			'--color-border-dark' => $this->util->lighten($colorMainBackground, 14),

			'--background-invert-if-dark' => 'invert(100%)',
		]);
	}
}
