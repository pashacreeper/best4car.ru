<?php

namespace Sto\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sto\CoreBundle\Entity\CompanyType;

class LoadDictionaryCompanyTypeData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $organizationTypes = [
            'A' => ['СТО' => [
                'A-1' => 'Официальная дилерская СТО',
                'A-2' => 'Независимая (недилерская) монобрэндовая СТО',
                'A-3' => 'Независимая (недилерская) мультибрэндовая СТО'
            ]],
            'B' => [
                'Автосалон' => [
                    'B-1' => 'Официальный дилер, мононбрэндовый автосалон',
                    'B-2' => 'Независимый монобрэндовый автосалон',
                    'B-3' => 'Независимый мультибрэндовый автосалон'
                ]
            ],
            'C' => [
                'Паркинг' => [
                    'С-1' => 'Охраняемый отапливаемый паркинг',
                    'С-2' => 'Охраняемый неотапливаемый паркинг',
                    'С-3' => 'Охраняемая открытая автостоянка'
                ]
            ],
            'D' => [
                'Ремонт агрегатов и блоков управления' => [
                    'D-1' => 'Специализированная мастерская по ремонту узлов и агрегатов, без возможности снятия и установки их с автомобиля в условиях мастерской.',
                    'D-2' => 'Специализированная мастерская по ремонту узлов и агрегатов, с возможностью снятия и установки их с автомобиля в условиях мастерской'
                ]
            ],
            'E' => [
                'Мойка' => [
                    'E-1' => 'Ручная мойка',
                    'E-2' => 'Автоматическая портальная мойка',
                    'E-3' => 'Автоматическая проездная мойка самообслуживания, без участия персонала',
                    'E-4' => 'Автомоечный комплекс с различными видами моек и дополнительных услуг'
                ]
            ],
            'F' => [
                'Шиномонтаж' => [
                    'F-1' => 'Шиномонтажная мастерская стационарная',
                    'F-2' => 'Мобильный шиномонтаж'
                ]
            ],
            'G' => [
                'Юридические услуги' => [
                    'G-1' => 'Юридические компании',
                    'G-2' => 'Индивидуальный юридический консультант (частная практика)',
                    'G-3' => 'Общество защиты прав потребителей'
                ]
            ],
            'H' => [
                'Экспертиза' => [
                    'Н-1' => 'Экспертная компания',
                    'Н-2 Экспертное сообщество'
                ]
            ],
            'I' => [
                'Эвакуация и помощь на дороге' => [
                    'I-1' => 'Эвакуационная компания со своим автопарком эвакуаторов',
                    'I-2' => 'Компания оказывающая услуги по помощи автовладельцам на дороге, но не имеющая автоэвакуатора'
                ]
            ],
            'J' => [
                'Страхование' => [
                    'J-1' => 'Страховая компания',
                    'J-2' => 'Страховой агент',
                    'J-3' => 'Страховой брокер'
                ]
            ],
            'K' => [
                'ГосТехОсмотр' => [
                    'К-1' => 'Государственный пункт прохождения ГТО',
                    'К-2' => 'Коммерческий пункт прохождения ГТО',
                    'К-3' => 'СТО уполномоченная проводить ГТО'
                ]
            ],
            'L' => [
                'Установка сигнализаций' => [
                    'L-1' => 'Центр охранных систем',
                    'L-2' => 'Тюнинговая мастерская с широким набором услуг'
                ]
            ],
            'M' => [
                'МРЭО' => [
                    'М-1' => 'Государственное МРЭО',
                    'М-2' => 'Коммерческое МРЭО'
                ]
            ],
            'N' => [
                'Дополнительное оборудование' => [
                    'N-1' => 'Тюнинговая мастерская',
                    'N-2' => 'Установочный центр дополнительного оборудования'
                ]
            ],
            'O' => [
                'Автошкола' => [
                    'О-1' => 'Мотошкола',
                    'О-2' => 'Автошкола государственная',
                    'О-3' => 'Автошкола коммерческая',
                    'О-3' => 'Курсы контр-аварийной подготовки',
                    'О-4' => 'Курсы повышения водительского мастерства',
                    'О-5' => 'Секции и школы авто-мото спортивного направления'
                ]
            ],
            'P' => [
                'Запасные части' => [
                    'Р-1' => 'Поставщик новых запчастей',
                    'Р-2' => 'Поставщик аксесуаров',
                    'Р-3' => 'Авторазборка',
                    'Р-4' => 'Поставщик запчастей бывших в употреблении и восстановленных агрегатов'
                ]
            ],
            'Q' => [
                'Прокат автомобилей' => [
                    'Q-1' => 'Компания предоставляющая автомобили и автобусы с водителем (лимузины, патибасы) на торжественные мероприятия',
                    'Q-2' => 'Компания предоставляющая автомобили в прокат для повседневного пользования с водителем и без него',
                    'Q-3' => 'Компания предоставляющая мототехнику и автотехнику для участия в спортивных и увеселительных мероприятиях'
                ]
            ],
            'R' => [
                'Водительская медкомиссия' => [
                ]
            ],
            'X' => [
                'Автозаправочные станции' => []
            ],
            'Y' => [
                'Таксомоторные компании' => []
            ],
        ];

        $i = 1;
        $j = 1;
        $k = 1;
        foreach ($organizationTypes as $key => $value) {
            foreach ($value as $name => $types) {
                $dictionaryParent = (new CompanyType)
                    ->setShortName($key)
                    ->setName($name)
                ;

                chdir(__DIR__ . '/../../../../../');
                $from = "app/Resources/fixtures/company_type/iconMap/".rand(1,9).".png";
                $to = "web/storage/images/company_icon/".$k.".png";

                if (!file_exists($from)) {
                    $from = "app/Resources/fixtures/company_type/iconMap/1.png";
                }

                if (!is_dir(dirname($to))) {
                    mkdir(dirname($to), 0777, true);
                }

                if (!file_exists($to)) {
                    copy($from, $to);
                }
                $dictionaryParent->setIconNameMap($k.'.png');

                $manager->persist($dictionaryParent);
                $this->addReference("companiesTypesParent[{$i}]", $dictionaryParent);
                $this->addReference("companiesTypes[{$key}]", $dictionaryParent);
                $i++; $k++; if ($k==10) $k =1;

                if (!empty($types))
                    foreach ($types as $shortName => $typeName) {
                        $dictionaryChildren = (new CompanyType)
                            ->setShortName($shortName)
                            ->setName($typeName)
                            ->setParent($dictionaryParent)
                        ;

                        chdir(__DIR__ . '/../../../../../');
                        $from = "app/Resources/fixtures/company_type/iconMap/".rand(1,9).".png";
                        $to = "web/storage/images/company_icon/".$k.".png";

                        if (!file_exists($from)) {
                            $from = "app/Resources/fixtures/company_type/iconMap/1.png";
                        }

                        if (!is_dir(dirname($to))) {
                            mkdir(dirname($to), 0777, true);
                        }

                        if (!file_exists($to)) {
                            copy($from, $to);
                        }
                        $dictionaryChildren->setIconNameMap($k.'.png');

                        $manager->persist($dictionaryChildren);
                        $this->addReference("companiesTypesChildren[{$j}]", $dictionaryChildren);
                        $j++; $k++;
                        if ($k==10) $k =1;
                    }
            }
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 11;
    }
}
