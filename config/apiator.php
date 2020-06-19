<?php
declare(strict_types = 1);

return [
    /**
     * Префикс уникального идентификатора запроса
     */
    'requetIdPrefix'    => 'APTR_',
    /**
     * форматтер ответа JSON API
     * для реализации своего форматтера - реализуем \Patrikap\Apiator\Contracts\JsonResponseFormatterInterface
     * и прописываем класс своего форматтера тут
     */
    'responseFormatter' => \Patrikap\Apiator\Formatters\JsonResponseFormatter::class,
    'log'               => [
        /** флаг включения логирования */
        'enabled' => true,
        /** канал логирования */
        //'channel' => '',
        /**
         * Логгер для запросов/ответов API
         * для реализации свеого логгера - реализуем \Patrikap\Apiator\Contracts\JsonLoggerInterface
         * и прописываем класс своего логгера тут
         */
        'logger'  => \Patrikap\Apiator\Loggers\Logger::class,
        /** форматирование запроса для логирования */
        //'requestFormatter'=>'',
        /** форматирование ответа для логирования */
        //'responseFormatter'=>'',
    ],
];
